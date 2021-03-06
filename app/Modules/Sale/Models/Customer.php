<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/8
 * Time: 11:18
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ChineseRegion;
use Illuminate\Support\Facades\DB;
use App\Events\Deducted;
use App\Models\Currency;
use App\Modules\Index\Models\User;
use App\Modules\Finance\Models\Collection;
use App\Modules\Finance\Models\DeliveryOrderItemDeduction;

class Customer extends Model
{
    use SoftDeletes;

    // 付款方式
    static $payment_methods = [
        \PaymentMethod::CASH    => '现金',
        \PaymentMethod::CREDIT  => '货到付款',
        \PaymentMethod::MONTHLY => '月结',
    ];

    // 税率
    static $taxes = [
        \Tax::NONE      => ['display' => '不含税', 'rate' => 0],
        \Tax::THREE     => ['display' => '3%', 'rate' => 0.03],
        \Tax::SEVENTEEN => ['display' => '17%', 'rate' => 0.17],
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
    }

    public function logs()
    {
        return $this->hasMany(CustomerLog::class)->orderBy('id', 'desc');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function getPaymentMethodNameAttribute()
    {
        return isset(self::$payment_methods[$this->payment_method]) ? self::$payment_methods[$this->payment_method] : '';
    }

    public function syncContacts($contacts)
    {
        if (!$contacts || !is_array($contacts)) {
            return false;
        }

        // 将不在请求中的联系人删除
        CustomerContact::where('customer_id', $this->id)->whereNotIn('id', array_keys($contacts))->get()->map(function ($contact) {
            $contact->delete();
        });

        foreach ($contacts as $flag => $item) {
            CustomerContact::updateOrCreate(['id' => $flag], [
                'customer_id' => $this->id,
                'name' => $item['name'],
                'position' => $item['position'],
                'phone' => $item['phone'],
            ]);
        }

        return $this;
    }

    /**
     * 抵扣应收款明细
     *
     * @param $doi_ids
     * @return $this|bool
     * @throws \Exception
     */
    public function deduct($doi_ids)
    {
        if (!$doi_ids || !is_array($doi_ids)) {
            return false;
        }

        // 退货单未抵扣金额（人民币）
        $back_order_amounts = $this->back_order_amounts;
        // 未抵扣的付款单
        $remained_collections = Collection::where('customer_id', $this->id)
            ->where('is_finished', NO)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($doi_ids as $doi_id) {
            $delivery_order_item = DeliveryOrderItem::find($doi_id);
            $order_item = $delivery_order_item->orderItem;
            $order_currency = $order_item->order->currency;
            // 需要抵扣的金额(人民币)
            $amount = $order_item->price * $delivery_order_item->real_quantity * $order_currency->rate;

            // 如果有退货单，先用退货单抵扣
            foreach ($back_order_amounts as $return_order_id => $return_amount) {
                $return_order = ReturnOrder::find($return_order_id);
                $return_order_currency = $return_order->order->currency;
                if ($amount <= $return_amount) { // 退货单可以完全抵扣出货item金额
                    DeliveryOrderItemDeduction::create([
                        'delivery_order_item_id' => $doi_id,
                        'order_currency_code' => $order_currency->code,
                        'order_rate' => $order_currency->rate,
                        'return_order_id' => $return_order_id,
                        'deduct_currency_code' => $return_order_currency->code,
                        'deduct_rate' => $return_order_currency->rate,
                        'amount' => $amount,
                    ]);
                    $delivery_order_item->is_paid = YES;
                    $delivery_order_item->save();

                    // 退货单剩余未抵扣金额
                    $remained_return_amount = $return_amount - $amount;
                    if (0 == $remained_return_amount) {
                        // 如果刚好抵扣完，则需要将退货单的状态改为已完成，并删除掉这个键值，因为下次还要循环调用
                        $return_order->status = ReturnOrder::FINISHED;
                        $return_order->save();
                        unset($back_order_amounts[$return_order_id]);
                    }else {
                        // 如果没有抵扣完，那么退货单ID对应的值应该减掉相应的金额，用于下次循环调用
                        $back_order_amounts[$return_order_id] = $remained_return_amount;
                    }
                    continue 2; // 直接循环下一个出货单Item
                }else { // 退货单不够抵扣出货item金额
                    DeliveryOrderItemDeduction::create([
                        'delivery_order_item_id' => $doi_id,
                        'order_currency_code' => $order_currency->code,
                        'order_rate' => $order_currency->rate,
                        'return_order_id' => $return_order_id,
                        'deduct_currency_code' => $return_order_currency->code,
                        'deduct_rate' => $return_order_currency->rate,
                        'amount' => $return_amount,
                    ]);
                    $amount -= $return_amount;
                    // 该张退货单已经全部抵扣完
                    $return_order->status = ReturnOrder::FINISHED;
                    $return_order->save();
                    unset($back_order_amounts[$return_order_id]);
                }
            }

            // 退货单抵扣完后用收款单抵扣
            $remained_collection = $remained_collections->first();
            while ($remained_collection) {
                $collection_currency = $remained_collection->currency; // 收款单货币
                $remained_collection_amount = $remained_collection->remained_amount * $collection_currency->rate; // 收款单剩余金额（人民币）
                if ($amount <= $remained_collection_amount) { // 收款单可以完全抵扣
                    // 剩余未抵扣的金额
                    DeliveryOrderItemDeduction::create([
                        'delivery_order_item_id' => $doi_id,
                        'order_currency_code' => $order_currency->code,
                        'order_rate' => $order_currency->rate,
                        'collection_id' => $remained_collection->id,
                        'deduct_currency_code' => $collection_currency->code,
                        'deduct_rate' => $collection_currency->rate,
                        'amount' => $amount,
                    ]);
                    $remained_collection_amount -= $amount;
                    if (0 == $remained_collection_amount) {
                        $remained_collection->is_finished = YES;
                    }
                    $remained_collection->remained_amount = price_format($remained_collection_amount / $collection_currency->rate);

                    $remained_collection->save();
                    $amount = 0; // 已经完全抵扣，需要抵扣的金额置0
                }else { // 收款单不够抵扣
                    DeliveryOrderItemDeduction::create([
                        'delivery_order_item_id' => $doi_id,
                        'order_currency_code' => $order_currency->code,
                        'order_rate' => $order_currency->rate,
                        'collection_id' => $remained_collection->id,
                        'deduct_currency_code' => $collection_currency->code,
                        'deduct_rate' => $collection_currency->rate,
                        'amount' => $remained_collection_amount,
                    ]);
                    $amount -= $remained_collection_amount;
                    // 该收款单已经抵扣完
                    $remained_collection->remained_amount = 0;
                    $remained_collection->is_finished = YES;
                    $remained_collection->save();
                    // 将该收款单弹出
                    $remained_collections->shift();
                    $remained_collection = $remained_collections->first();
                }
                if (0 == $amount) {
                    break;
                }
            }

            if ($amount > 0) {
                throw new \Exception("选中的明细金额不可大于收款金额");
            }
            $delivery_order_item->is_paid = YES;
            $delivery_order_item->save();
        }

        // 触发抵扣事件
        event(new Deducted($doi_ids));

        return $this;
    }

    public function state()
    {
        return $this->belongsTo(ChineseRegion::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(ChineseRegion::class, 'city_id');
    }

    public function county()
    {
        return $this->belongsTo(ChineseRegion::class, 'county_id');
    }

    public function getFullAddressAttribute()
    {
        $full_address = '';
        if ($this->state_id) {
            $full_address .= $this->state->name;
        }
        if ($this->city_id) {
            $full_address .= $this->city->name;
        }
        if ($this->county_id) {
            $full_address .= $this->county->name;
        }
        $full_address .= $this->street_address;

        return $full_address;
    }

    /**
     * 未付款Item
     *
     * @return mixed
     */
    public function unpaidItems()
    {
        return $this->hasManyThrough(DeliveryOrderItem::class, DeliveryOrder::class)
            ->leftJoin('orders AS o', 'o.id', '=', 'delivery_order_items.order_id')
            ->leftJoin('order_items AS oi', 'oi.id', '=', 'delivery_order_items.order_item_id')
            ->leftJoin('goods_skus AS gs', 'gs.id', '=', 'oi.sku_id')
            ->leftJoin('currencies AS c', 'c.code', '=', 'o.currency_code')
            ->where('delivery_orders.status', DeliveryOrder::FINISHED)
            ->where('delivery_orders.customer_id', $this->id)
            ->where('delivery_order_items.is_paid', NO)
            ->where('delivery_order_items.real_quantity', '>', 0)
            ->select([
                'delivery_order_items.id AS delivery_order_item_id',
                'o.code AS order_code',
                'gs.code AS sku_code',
                'oi.title',
                'oi.price',
                'oi.quantity AS order_quantity',
                'c.rate',
                'c.code AS currency_code',
                'delivery_order_items.quantity AS delivery_quantity',
                'delivery_order_items.real_quantity',
                'delivery_orders.code AS delivery_code',
                'delivery_order_items.created_at AS delivery_at',
                DB::raw('ROUND(oi.price * c.rate, 2) AS cny_price'),
                DB::raw('ROUND(oi.price * c.rate * delivery_order_items.real_quantity, 2) AS cny_amount'),
                DB::raw('delivery_order_items.real_quantity * oi.price AS amount'),
            ])
            ->orderBy('delivery_order_items.id', 'asc');
    }

    /**
     * 退货单
     *
     * @return mixed
     */
    public function backOrders()
    {
        return $this->hasManyThrough(ReturnOrder::class, Order::class)
            ->where('return_orders.method', ReturnOrder::BACK)
            ->where('return_orders.status', ReturnOrder::ENTRIED);
    }

    /**
     * 退货单未抵扣金额(人民币)，每个订单为一行数据
     *
     * @return array
     */
    public function getBackOrderAmountsAttribute()
    {
        $back_order_amounts = [];
        foreach ($this->backOrders as $return_order) {
            $back_order_amounts[$return_order->id] = $return_order->undeducted_amount;
        }

        return $back_order_amounts;
    }

    /**
     * 退货金额(人民币)
     *
     * @return int
     */
    public function getBackAmountAttribute()
    {
        return array_sum($this->back_order_amounts);
    }

    /**
     * 付款单剩余金额
     *
     * @return number
     */
    public function getTotalRemainedAmountAttribute()
    {
        $total_remained_amount = 0;
        $collections = Collection::where('customer_id', $this->id)->where('is_finished', NO)->get();
        foreach ($collections as $collection) {
            $total_remained_amount += $collection->remained_amount * $collection->currency->rate;
        }

        return $total_remained_amount;
    }

    /**
     * 进行中的订单
     *
     * @return mixed
     */
    public function processingOrder()
    {
        return $this->hasMany(Order::class)->whereIn('status', [Order::AGREED, Order::FINISHED])->where('payment_status', 1);
    }

    /**
     * 剩余额度(人民币)
     *
     * @return mixed|null
     */
    public function getRemainedCreditAttribute()
    {
        if (\PaymentMethod::CREDIT != $this->payment_method) {
            return null;
        }

        $processing_order_amount = 0; // 进行中订单的金额
        $real_paid_amount = 0;
        foreach ($this->processingOrder as $order) {
            $real_paid_amount += $order->real_paid_amount;
            foreach ($order->items as $item) {
                $processing_order_amount += $item->price * ($item->quantity - $item->back_quantity) * $order->currency->rate;
            }
        }

        // 剩余额度 = 信用额度 - 进行中订单金额 + 订单付款金额 + 付款单剩余金额 + 已付款完成订单的退款金额
        return $this->credit - $processing_order_amount + $real_paid_amount + $this->total_remained_amount + $this->finished_order_back_amount;
    }

    public function getTaxNameAttribute()
    {
        return isset(self::$taxes[$this->tax]) ? self::$taxes[$this->tax]['display'] : '';
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function pendingPaymentMethodApplication()
    {
        return $this->hasOne(PaymentMethodApplication::class)->whereIn('status', [PaymentMethodApplication::PENDING_REVIEW, PaymentMethodApplication::REJECTED])->orderBy('id', 'desc');
    }

    /**
     * 已付款完成订单的退货金额
     *
     * @return int
     */
    public function getFinishedOrderBackAmountAttribute()
    {
        $back_amount = 0;

        $return_order_items = ReturnOrderItem::leftJoin('return_orders AS ro', 'ro.id', '=', 'return_order_items.return_order_id')
            ->leftJoin('orders AS o', 'o.id', '=', 'ro.order_id')
            ->leftJoin('order_items AS oi', 'oi.id', '=', 'return_order_items.order_item_id')
            ->leftJoin('currencies AS c', 'c.code', '=', 'o.currency_code')
            ->whereNull('ro.deleted_at')
            ->whereNull('o.deleted_at')
            ->where('o.customer_id', $this->id)
            ->where('ro.method', ReturnOrder::BACK)
            ->where('ro.status', ReturnOrder::ENTRIED)
            ->where('o.status', Order::FINISHED)
            ->where('o.payment_status', Order::FINISHED_PAYMENT)
            ->select(['return_order_items.quantity AS back_quantity', 'oi.price', 'c.rate'])
            ->get();
        foreach ($return_order_items as $item) {
            $back_amount += $item->back_quantity * $item->price * $item->rate;
        }

        return $back_amount;
    }

    /**
     * 是否可删除
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        // 下过订单的客户不可删除
        if (Order::where('customer_id', $this->id)->exists()) {
            return false;
        }

        // 付过款的客户不可删除
        if (Collection::where('customer_id', $this->id)->exists()) {
            return false;
        }

        return true;
    }
}