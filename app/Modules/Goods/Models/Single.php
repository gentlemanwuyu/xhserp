<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 15:52
 */

namespace App\Modules\Goods\Models;



class Single extends Goods
{
    public function getProductIdAttribute()
    {
        return SingleProduct::where('goods_id', $this->id)->value('product_id');
    }

    public function syncSkus($skus)
    {
        if (!$skus || !is_array($skus)) {
            return false;
        }

        foreach ($skus as $product_sku_id => $item) {
            $goods_sku_id = SingleSkuProductSku::where('product_sku_id', $product_sku_id)->value('goods_sku_id');
            if (!$goods_sku_id) {
                $goods_sku = GoodsSku::create([
                    'goods_id' => $this->id,
                    'code' => $item['code'],
                    'lowest_price' => $item['lowest_price'],
                    'msrp' => $item['msrp'],
                ]);
                SingleSkuProductSku::create(['product_sku_id' => $product_sku_id, 'goods_sku_id' => $goods_sku->id]);
            }else {
                GoodsSku::withTrashed()->update(['id' => $goods_sku_id], [
                    'code' => $item['code'],
                    'lowest_price' => $item['lowest_price'],
                    'msrp' => $item['msrp'],
                ]);
            }
        }

        return $this;
    }
}