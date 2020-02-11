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
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Finance\Models\Payment;
use App\Modules\Finance\Models\PaymentItem;

class Supplier extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

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

    /**
     * 抵扣应付款明细
     *
     * @param $entry_ids
     * @return $this|bool
     * @throws \Exception
     */
    public function deduct($entry_ids)
    {
        if (!is_array($entry_ids)) {
            return false;
        }

        // 未抵扣的付款单
        $remained_payments = Payment::where('supplier_id', $this->id)
            ->where('is_finished', 0)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($entry_ids as $entry_id) {
            $entry = SkuEntry::find($entry_id);
            $order_item = $entry->orderItem;
            // 需要抵扣的金额
            $amount = $order_item->price * $entry->quantity;
            $remained_payment = $remained_payments->first();
            while ($remained_payment) {
                if ($amount <= $remained_payment->remained_amount) {
                    // 剩余未抵扣的金额
                    $remained_amount = $remained_payment->remained_amount;
                    PaymentItem::create([
                        'payment_id' => $remained_payment->id,
                        'entry_id' => $entry_id,
                        'amount' => $amount,
                    ]);
                    $remained_amount -= $amount;
                    $remained_payment->remained_amount = $remained_amount;
                    if (0 == $remained_amount) {
                        $remained_payment->is_finished = 1;
                    }

                    $remained_payment->save();
                    $amount = 0; // 已经完全抵扣，需要抵扣的金额置0
                }else {
                    PaymentItem::create([
                        'payment_id' => $remained_payment->id,
                        'entry_id' => $entry_id,
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

    public function contacts()
    {
        return $this->hasMany(SupplierContact::class);
    }

    public function getPaymentMethodNameAttribute()
    {
        return isset(self::$payment_methods[$this->payment_method]) ? self::$payment_methods[$this->payment_method] : '';
    }

    /**
     * 未付款Item
     *
     * @return mixed
     */
    public function getUnpaidItemsAttribute()
    {
        return SkuEntry::leftJoin('purchase_order_items AS poi', 'poi.id', '=', 'sku_entries.order_item_id')
            ->leftJoin('purchase_orders AS po', 'po.id', '=', 'poi.order_id')
            ->leftJoin('product_skus AS ps', 'ps.id', '=', 'sku_entries.sku_id')
            ->where('po.supplier_id', $this->id)
            ->where('sku_entries.is_paid', 0)
            ->get([
                'sku_entries.id AS entry_id',
                'po.code AS order_code',
                'ps.code AS sku_code',
                'poi.price',
                'poi.quantity AS order_quantity',
                'sku_entries.quantity AS entry_quantity',
                DB::raw('sku_entries.quantity * poi.price AS amount'),
                'sku_entries.created_at AS entry_at',
            ]);
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
}