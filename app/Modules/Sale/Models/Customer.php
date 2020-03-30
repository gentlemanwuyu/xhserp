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
use App\Modules\Index\Models\User;
use App\Modules\Finance\Models\Collection;
use App\Modules\Finance\Models\CollectionItem;

class Customer extends Model
{
    use SoftDeletes;

    static $payment_methods = [
        1 => '现金',
        2 => '货到付款',
        3 => '月结',
    ];

    static $taxes = [
        1 => ['display' => '不含税', 'rate' => 0],
        2 => ['display' => '3%', 'rate' => 0.03],
        3 => ['display' => '17%', 'rate' => 0.17],
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
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
        if (!is_array($doi_ids)) {
            return false;
        }

        // 未抵扣的付款单
        $remained_collections = Collection::where('customer_id', $this->id)
            ->where('is_finished', 0)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($doi_ids as $doi_id) {
            $delivery_order_item = DeliveryOrderItem::find($doi_id);
            $order_item = $delivery_order_item->orderItem;
            // 需要抵扣的金额
            $amount = $order_item->price * $delivery_order_item->real_quantity;
            $remained_collection = $remained_collections->first();
            while ($remained_collection) {
                if ($amount <= $remained_collection->remained_amount) {
                    // 剩余未抵扣的金额
                    $remained_amount = $remained_collection->remained_amount;
                    CollectionItem::create([
                        'collection_id' => $remained_collection->id,
                        'doi_id' => $doi_id,
                        'amount' => $amount,
                    ]);
                    $remained_amount -= $amount;
                    $remained_collection->remained_amount = $remained_amount;
                    if (0 == $remained_amount) {
                        $remained_collection->is_finished = 1;
                    }

                    $remained_collection->save();
                    $amount = 0; // 已经完全抵扣，需要抵扣的金额置0
                }else {
                    CollectionItem::create([
                        'collection_id' => $remained_collection->id,
                        'doi_id' => $doi_id,
                        'amount' => $remained_collection->remained_amount,
                    ]);
                    $amount -= $remained_collection->remained_amount;
                    // 该收款单已经抵扣完
                    $remained_collection->remained_amount = 0;
                    $remained_collection->is_finished = 1;
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
            $delivery_order_item->is_paid = 1;
            $delivery_order_item->save();
        }

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
            ->where('delivery_orders.status', '2')
            ->where('delivery_orders.customer_id', $this->id)
            ->where('delivery_order_items.is_paid', 0)
            ->where('delivery_order_items.real_quantity', '>', 0)
            ->select([
                'delivery_order_items.id AS delivery_order_item_id',
                'o.code AS order_code',
                'gs.code AS sku_code',
                'oi.title',
                'oi.price',
                'oi.quantity AS order_quantity',
                'delivery_order_items.quantity AS delivery_quantity',
                'delivery_order_items.real_quantity',
                'delivery_orders.code AS delivery_code',
                'delivery_order_items.created_at AS delivery_at',
                DB::raw('delivery_order_items.real_quantity * oi.price AS amount'),
            ])
            ->orderBy('delivery_order_items.id', 'asc');
    }

    /**
     * 付款单剩余金额
     *
     * @return number
     */
    public function getTotalRemainedAmountAttribute()
    {
        $collections = Collection::where('customer_id', $this->id)->where('is_finished', 0)->get()->toArray();

        return array_sum(array_column($collections, 'remained_amount'));
    }

    /**
     * 进行中的订单
     *
     * @return mixed
     */
    public function processingOrder()
    {
        return $this->hasMany(Order::class)->whereIn('status', [3, 4])->where('payment_status', 1);
    }

    /**
     * 剩余额度
     *
     * @return mixed|null
     */
    public function getRemainedCreditAttribute()
    {
        if (2 != $this->payment_method) {
            return null;
        }

        $used_credit = 0;
        $orders = $this->processingOrder;
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $used_credit += $item->price * ($item->quantity - $item->paid_quantity);
            }
        }

        return $this->credit - $used_credit + $this->total_remained_amount;
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
        return $this->hasOne(PaymentMethodApplication::class)->whereIn('status', [1, 2])->orderBy('id', 'desc');
    }
}