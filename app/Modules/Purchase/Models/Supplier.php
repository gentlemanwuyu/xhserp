<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/7
 * Time: 13:32
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Currency;
use App\Models\ChineseRegion;
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Finance\Models\Payment;
use App\Modules\Finance\Models\SkuEntryDeduction;

class Supplier extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

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

    /**
     * 抵扣应付款明细
     *
     * @param $entry_ids
     * @return $this|bool
     * @throws \Exception
     */
    public function deduct($entry_ids)
    {
        if (!$entry_ids || !is_array($entry_ids)) {
            return false;
        }

        $back_order_amounts = $this->back_order_amounts;
        // 未抵扣的付款单
        $remained_payments = Payment::where('supplier_id', $this->id)
            ->where('is_finished', 0)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($entry_ids as $entry_id) {
            $entry = SkuEntry::find($entry_id);
            $purchase_order_item = $entry->purchaseOrderItem;
            // 需要抵扣的金额
            $amount = $purchase_order_item->price * $entry->real_quantity;

            // 如果有退货单，先用退货单抵扣
            foreach ($back_order_amounts as $purchase_return_order_id => $back_amount) {
                $purchase_return_order = PurchaseReturnOrder::find($purchase_return_order_id);
                if ($amount <= $back_amount) { // 退货单可以完全抵扣入库金额
                    SkuEntryDeduction::create([
                        'sku_entry_id' => $entry_id,
                        'purchase_return_order_id' => $purchase_return_order_id,
                        'amount' => $amount,
                    ]);
                    $entry->is_paid = 1;
                    $entry->save();

                    // 退货单剩余未抵扣金额
                    $remained_back_amount = $back_amount - $amount;
                    if (0 == $remained_back_amount) {
                        // 如果刚好抵扣完，则需要将退货单的状态改为已完成，并删除掉这个键值，因为下次还要循环调用
                        $purchase_return_order->status = 3;
                        $purchase_return_order->save();
                        unset($back_order_amounts[$purchase_return_order_id]);
                    }else {
                        // 如果没有抵扣完，那么退货单ID对应的值应该减掉相应的金额，用于下次循环调用
                        $back_order_amounts[$purchase_return_order_id] = $remained_back_amount;
                    }
                    continue 2; // 直接循环下一个entry
                }else { // 退货单不够抵扣入库金额
                    SkuEntryDeduction::create([
                        'sku_entry_id' => $entry_id,
                        'purchase_return_order_id' => $purchase_return_order_id,
                        'amount' => $back_amount,
                    ]);
                    $amount -= $back_amount;
                    // 该张退货单已经全部抵扣完
                    $purchase_return_order->status = 3;
                    $purchase_return_order->save();
                    unset($back_order_amounts[$purchase_return_order_id]);
                }
            }

            // 退货单抵扣完后用付款单抵扣
            $remained_payment = $remained_payments->first();
            while ($remained_payment) {
                if ($amount <= $remained_payment->remained_amount) { // 付款单可以完全抵扣
                    // 剩余未抵扣的金额
                    $remained_payment_amount = $remained_payment->remained_amount;
                    SkuEntryDeduction::create([
                        'sku_entry_id' => $entry_id,
                        'payment_id' => $remained_payment->id,
                        'amount' => $amount,
                    ]);
                    $remained_payment_amount -= $amount;
                    $remained_payment->remained_amount = $remained_payment_amount;
                    if (0 == $remained_payment_amount) {
                        $remained_payment->is_finished = 1;
                    }

                    $remained_payment->save();
                    $amount = 0; // 已经完全抵扣，需要抵扣的金额置0
                }else { // 收款单不够抵扣
                    SkuEntryDeduction::create([
                        'sku_entry_id' => $entry_id,
                        'payment_id' => $remained_payment->id,
                        'amount' => $remained_payment->remained_amount,
                    ]);

                    $amount -= $remained_payment->remained_amount;
                    // 该付款单已经抵扣完
                    $remained_payment->remained_amount = 0;
                    $remained_payment->is_finished = 1;
                    $remained_payment->save();
                    // 将该付款单弹出
                    $remained_payments->shift();
                    $remained_payment = $remained_payments->first();
                }
                if (0 == $amount) {
                    break;
                }
            }

            if ($amount > 0) {
                throw new \Exception("选中的明细金额不可大于付款金额");
            }
            $entry->is_paid = 1;
            $entry->save();
        }

        return $this;
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function contacts()
    {
        return $this->hasMany(SupplierContact::class);
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

    /**
     * 未付款Item
     *
     * @return mixed
     */
    public function unpaidItems()
    {
        return $this->hasManyThrough(SkuEntry::class, PurchaseOrder::class)
            ->leftJoin('purchase_order_items AS poi', 'poi.id', '=', 'sku_entries.purchase_order_item_id')
            ->leftJoin('product_skus AS ps', 'ps.id', '=', 'poi.sku_id')
            ->where('purchase_orders.supplier_id', $this->id)
            ->where('sku_entries.is_paid', 0)
            ->where('sku_entries.real_quantity', '>', 0)
            ->select([
                'sku_entries.id AS entry_id',
                'purchase_orders.code AS purchase_order_code',
                'ps.code AS sku_code',
                'poi.price',
                'poi.quantity AS order_quantity',
                'sku_entries.quantity AS entry_quantity',
                'sku_entries.real_quantity',
                DB::raw('sku_entries.real_quantity * poi.price AS amount'),
                'sku_entries.created_at AS entried_at',
            ])
            ->orderBy('sku_entries.id', 'asc');
    }

    /**
     * 退货单
     *
     * @return mixed
     */
    public function backOrders()
    {
        return $this->hasManyThrough(PurchaseReturnOrder::class, PurchaseOrder::class)
            ->where('purchase_return_orders.method', 2)
            ->where('purchase_return_orders.status', 2);
    }

    /**
     * 退货单未抵扣金额，每个订单为一行数据
     *
     * @return array
     */
    public function getBackOrderAmountsAttribute()
    {
        $back_order_amounts = [];
        foreach ($this->backOrders as $purchase_return_order) {
            $back_order_amounts[$purchase_return_order->id] = $purchase_return_order->undeducted_amount;
        }

        return $back_order_amounts;
    }

    /**
     * 退货金额
     *
     * @return int
     */
    public function getBackAmountAttribute()
    {
        return array_sum($this->back_order_amounts);
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
        SupplierContact::where('supplier_id', $this->id)->whereNotIn('id', array_keys($contacts))->get()->map(function ($contact) {
            $contact->delete();
        });

        foreach ($contacts as $flag => $item) {
            SupplierContact::updateOrCreate(['id' => $flag], [
                'supplier_id' => $this->id,
                'name' => $item['name'],
                'position' => $item['position'],
                'phone' => $item['phone'],
            ]);
        }

        return $this;
    }

    /**
     * 付款单剩余金额
     *
     * @return number
     */
    public function getTotalRemainedAmountAttribute()
    {
        $payments = Payment::where('supplier_id', $this->id)->where('is_finished', 0)->get()->toArray();

        return array_sum(array_column($payments, 'remained_amount'));
    }

    public function getTaxNameAttribute()
    {
        return isset(self::$taxes[$this->tax]) ? self::$taxes[$this->tax]['display'] : '';
    }

    /**
     * 完整地址
     *
     * @return string
     */
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
     * 是否可删除
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        return !PurchaseOrder::where('supplier_id', $this->id)->exists();
    }
}