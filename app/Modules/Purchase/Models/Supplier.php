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

class Supplier extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    static $payment_methods = [
        1 => '现金',
        2 => '货到付款',
        3 => '月结',
    ];

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
        $payments = Payment::where('is_finished', 0)->get()->toArray();

        return array_sum(array_column($payments, 'remained_amount'));
    }
}