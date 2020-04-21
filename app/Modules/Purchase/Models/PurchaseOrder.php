<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/10
 * Time: 19:02
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CodeTrait;
use App\Models\Currency;
use App\Modules\Index\Models\User;
use App\Modules\Warehouse\Models\SkuEntry;

class PurchaseOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhspo';

    static $statuses = [
        1 => '待审核',
        2 => '已驳回',
        3 => '已通过',
        4 => '已完成',
        5 => '已取消',
    ];

    public function syncItems($items)
    {
        if (!$items || !is_array($items)) {
            return false;
        }

        // 将不在请求中的item删除
        PurchaseOrderItem::where('purchase_order_id', $this->id)->whereNotIn('id', array_keys($items))->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($items as $flag => $item) {
            $item_data = [
                'product_id' => $item['product_id'],
                'sku_id' => $item['sku_id'],
                'title' => $item['title'],
                'unit' => $item['unit'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'note' => $item['note'],
            ];
            $order_item = PurchaseOrderItem::find($flag);
            if (!$order_item) {
                $item_data['purchase_order_id'] = $this->id;
                PurchaseOrderItem::create($item_data);
            }else {
                $order_item->update($item_data);
            }
        }

        return $this;
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseReturnOrders()
    {
        return $this->hasMany(PurchaseReturnOrder::class)->OrderBy('id', 'desc');
    }

    /**
     * 已出库的换货退货单
     *
     * @return mixed
     */
    public function egressExchangeReturnOrders()
    {
        return $this->hasMany(PurchaseReturnOrder::class)->where('method', 1)->where('status', 2)->OrderBy('id', 'desc');
    }

    public function entryItems()
    {
        return $this->hasManyThrough(SkuEntry::class, PurchaseOrderItem::class);
    }

    public function getPaymentMethodNameAttribute()
    {
        return isset(Supplier::$payment_methods[$this->payment_method]) ? Supplier::$payment_methods[$this->payment_method] : '';
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getTaxNameAttribute()
    {
        return isset(Supplier::$taxes[$this->tax]) ? Supplier::$taxes[$this->tax]['display'] : '';
    }

    /**
     * 是否可退
     *
     * @return bool
     */
    public function getReturnableAttribute()
    {
        return !$this->items->filter(function ($item) {
            return $item->returnable_quantity;
        })->isEmpty();
    }

    public function getDeletableAttribute()
    {
        // 有入库记录的订单不可删除
        if (SkuEntry::where('purchase_order_id', $this->id)->exists()) {
            return false;
        }

        return true;
    }
}