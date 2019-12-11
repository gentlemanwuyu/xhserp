<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 11:03
 */

namespace App\Modules\Goods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Product\Models\Inventory;

class GoodsSku extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getProductSkuId($product_id)
    {
        return ComboSkuProductSku::where('goods_sku_id', $this->id)->where('product_id', $product_id)->value('product_sku_id');
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function getStockAttribute()
    {
        $goods = $this->goods;

        if (1 == $goods->type) {
            $product_sku_id = SingleSkuProductSku::where('goods_sku_id', $this->id)->value('product_sku_id');

            return Inventory::where('sku_id', $product_sku_id)->value('stock');
        }elseif (2 == $goods->type) {
            $products = array_column(ComboProduct::where('goods_id', $goods->id)->get()->toArray(), 'quantity', 'product_id');
            $product_sku_ids = array_column(ComboSkuProductSku::where('goods_sku_id', $this->id)->get()->toArray(), 'product_sku_id', 'product_id');
            foreach ($product_sku_ids as $product_id => $product_sku_id) {
                $stock = Inventory::where('sku_id', $product_sku_id)->value('stock');
                if (!$stock) {
                    $stock = 0;
                }

                $valid_stock = floor($stock/$products[$product_id]);

                if (!isset($min_stock) || $valid_stock < $min_stock) {
                    $min_stock = $valid_stock;
                }
            }

            return $min_stock;
        }else {
            return null;
        }
    }
}