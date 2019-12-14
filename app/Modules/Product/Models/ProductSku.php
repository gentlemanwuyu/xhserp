<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/2
 * Time: 14:10
 */

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Goods\Models\SingleSkuProductSku;
use App\Modules\Goods\Models\GoodsSku;
use App\Modules\Warehouse\Models\Inventory;
use App\Modules\Purchase\Models\PurchaseOrderItem;

class ProductSku extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'sku_id');
    }

    public function getSingleSkuAttribute()
    {
        $goods_sku_id = SingleSkuProductSku::where('product_sku_id', $this->id)->value('goods_sku_id');

        return $goods_sku_id ? GoodsSku::find($goods_sku_id) : null;
    }

    public function getPurchaseOrderItemsAttribute()
    {
        return PurchaseOrderItem::leftJoin('purchase_orders AS po', 'po.id', '=', 'purchase_order_items.order_id')
            ->where('purchase_order_items.sku_id', $this->id)
            ->where('po.status', 3)
            ->where('purchase_order_items.delivery_status', 1)
            ->get(['purchase_order_items.*'])
            ->map(function ($order_item) {
                $order_item->order->supplier;

                return $order_item;
            });
    }
}