<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 20:42
 */

namespace App\Modules\Goods\Models;

use Illuminate\Support\Facades\DB;
use App\Modules\Product\Models\Product;

class Combo extends Goods
{
    public function syncSkus($skus)
    {
        if (!$skus || !is_array($skus)) {
            return false;
        }

        // 将不在请求中的sku删除
        GoodsSku::where('goods_id', $this->id)->whereNotIn('id', array_keys($skus))->get()->map(function ($goods_sku) {
            $goods_sku->delete();
            ComboSkuProductSku::where('goods_sku_id', $goods_sku)->delete();
        });

        foreach ($skus as $flag => $item) {
            $goods_sku = GoodsSku::updateOrCreate(['id' => $flag], [
                'goods_id' => $this->id,
                'code' => $item['code'],
                'lowest_price' => $item['lowest_price'],
                'msrp' => $item['msrp'],
            ]);

            foreach ($item['parts'] as $product_id => $product_sku_id) {
                DB::table('combo_sku_product_skus')->updateOrInsert(['goods_sku_id' => $goods_sku->id, 'product_id' => $product_id], [
                    'goods_sku_id' => $goods_sku->id,
                    'product_id' => $product_id,
                    'product_sku_id' => $product_sku_id,
                ]);
            }
        }

        return $this;
    }

    public function getProductIdsAttribute()
    {
        return array_column(ComboProduct::where('goods_id', $this->id)->get(['product_id', 'quantity'])->toArray(), 'quantity', 'product_id');
    }

    public function getProductsAttribute()
    {
        $product_ids = $this->product_ids;
        return Product::whereIn('id', array_keys($product_ids))->get()->map(function ($product) use ($product_ids) {
            $product->quantity = isset($product_ids[$product->id]) ? $product_ids[$product->id] : 0;

            return $product;
        });
    }
}