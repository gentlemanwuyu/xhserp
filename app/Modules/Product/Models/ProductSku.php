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
use App\Modules\Purchase\Models\PurchaseOrder;

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

    public function getPurchaseOrdersAttribute()
    {
        return PurchaseOrder::leftJoin('purchase_order_items AS poi', 'poi.order_id', '=', 'purchase_orders.id')
            ->where('poi.sku_id', $this->id)
            ->where('purchase_orders.status', 3)
            ->where('poi.delivery_status', 1)
            ->get([
                'purchase_orders.*',
                'order_id',
                'product_id',
                'sku_id',
                'title',
                'unit',
                'quantity',
                'price',
                'delivery_date',
                'note',
                'delivery_status',
                'poi.created_at AS item_created_at',
                'poi.updated_at AS item_updated_at',
            ])->map(function ($order) {
                $order->supplier;

                return $order;
            });
    }
}