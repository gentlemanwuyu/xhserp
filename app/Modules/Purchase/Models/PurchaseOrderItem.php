<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/10
 * Time: 19:05
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductSku;
use App\Modules\Warehouse\Models\SkuEntry;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'sku_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * 待换货Item
     *
     * @return mixed
     */
    public function pendingExchangeItems()
    {
        return $this->hasMany(PurchaseReturnOrderItem::class)
            ->leftJoin('purchase_return_orders AS pro', 'pro.id', '=', 'purchase_return_order_items.purchase_return_order_id')
            ->leftJoin('sku_entry_exchanges AS see', 'see.purchase_return_order_item_id', '=', 'purchase_return_order_items.id')
            ->where('pro.method', 1)
            ->where('pro.status', 2)
            ->select([
                'purchase_return_order_items.*',
                DB::raw('IFNULL(SUM(see.quantity), 0) AS entried_quantity')
            ])
            ->groupBy('purchase_return_order_items.id')
            ->havingRaw('purchase_return_order_items.quantity > entried_quantity')
            ->orderBy('purchase_return_order_items.id', 'asc');
    }

    /**
     * 已入库数量
     *
     * @return number
     */
    public function getEntriedQuantityAttribute()
    {
        $entries = SkuEntry::where('purchase_order_item_id', $this->id)->pluck('quantity')->toArray();

        return $entries ? array_sum($entries) : 0;
    }

    public function getBackQuantityAttribute()
    {
        $back_quantities = PurchaseReturnOrderItem::leftJoin('purchase_return_orders AS pro', 'pro.id', '=', 'purchase_return_order_items.purchase_return_order_id')
            ->where('purchase_order_item_id', $this->id)
            ->where('pro.method', 2)
            ->where('pro.status', 2)
            ->pluck('quantity')
            ->toArray();

        return $back_quantities ? array_sum($back_quantities) : 0;
    }

    /**
     * 可退数量 = 已入库数量 - 退货数量
     *
     * @return mixed
     */
    public function getReturnableQuantityAttribute()
    {
        $return_quantities = PurchaseReturnOrderItem::where('purchase_order_item_id', $this->id)->pluck('quantity')->toArray();

        $return_quantity = $return_quantities ? array_sum($return_quantities) : 0;

        return $this->entried_quantity - $return_quantity;
    }

    /**
     * 已出库的换货数量
     *
     * @return int|number
     */
    public function getEgressExchangeQuantityAttribute()
    {
        $egress_exchange_quantities = PurchaseReturnOrderItem::leftJoin('purchase_return_orders AS pro', 'pro.id', '=', 'purchase_return_order_items.purchase_return_order_id')
            ->where('purchase_return_order_items.purchase_order_item_id', $this->id)
            ->whereNull('pro.deleted_at')
            ->where('pro.method', 1)
            ->whereIn('pro.status', [2, 3])
            ->pluck('quantity')
            ->toArray();

        return $egress_exchange_quantities ? array_sum($egress_exchange_quantities) : 0;
    }

    /**
     * 待入库数量
     *
     * @return mixed
     */
    public function getPendingEntryQuantityAttribute()
    {
        // 订单数量 + 已出库的换货数量 - 已入库数量
        return $this->quantity + $this->egress_exchange_quantity - $this->entried_quantity;
    }
}