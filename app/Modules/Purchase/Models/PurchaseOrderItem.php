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
     * 已入库数量
     *
     * @return number
     */
    public function getEntriedQuantityAttribute()
    {
        $entries = SkuEntry::where('purchase_order_item_id', $this->id)->pluck('quantity')->toArray();

        return $entries ? array_sum($entries) : 0;
    }

    /**
     * 已退货数量
     *
     * @return int|number
     */
    public function getReturnedQuantityAttribute()
    {
        $returned_quantities = PurchaseReturnOrderItem::where('purchase_order_item_id', $this->id)->pluck('quantity')->toArray();

        return $returned_quantities ? array_sum($returned_quantities) : 0;
    }

    /**
     * 可退数量 = 已入库数量 - 已退数量
     *
     * @return mixed
     */
    public function getReturnableQuantityAttribute()
    {
        return $this->entried_quantity - $this->returned_quantity;
    }
}