<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/2
 * Time: 14:10
 */

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Goods\Models\GoodsSku;
use App\Modules\Goods\Models\ComboSkuProductSku;
use App\Modules\Goods\Models\SingleSkuProductSku;
use App\Modules\Warehouse\Models\Inventory;
use App\Modules\Warehouse\Models\InventoryLog;
use App\Modules\Purchase\Models\PurchaseOrderItem;

class ProductSku extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * 重写delete方法
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        // 删除之前先删除inventory
        $inventory = $this->inventory;
        if ($inventory) {
            $inventory->delete();
        }

        return parent::delete();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'sku_id');
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class, 'sku_id')->orderBy('id', 'desc');
    }

    /**
     * 采购订单Items
     *
     * @return mixed
     */
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'sku_id')
            ->leftJoin('purchase_orders AS po', 'po.id', '=', 'purchase_order_items.purchase_order_id')
            ->where('purchase_order_items.sku_id', $this->id)
            ->where(function ($query) {
                $query->where('po.status', 3)->orWhere('po.exchange_status', 1);
            })
            ->select(['purchase_order_items.*']);
    }

    public function getSingleSkuAttribute()
    {
        $goods_sku_id = SingleSkuProductSku::where('product_sku_id', $this->id)->value('goods_sku_id');
        if (!$goods_sku_id) {
            return null;
        }

        $single_sku = GoodsSku::find($goods_sku_id);
        $single_sku->setAppends(['deletable']);

        return $single_sku;
    }

    /**
     * 是否可删除
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        // 跟商品有关联的SKU不可删除
        if (SingleSkuProductSku::where('product_sku_id', $this->id)->exists() || ComboSkuProductSku::where('product_sku_id', $this->id)->exists()) {
            return false;
        }

        // 下过采购单的产品不可删除
        if (PurchaseOrderItem::where('sku_id', $this->id)->exists()) {
            return false;
        }

        // 有库存的产品不可删除
        $inventory = $this->inventory;
        if ($inventory && 0 < $inventory->stock) {
            return false;
        }

        return true;
    }
}