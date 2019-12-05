<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 20:42
 */

namespace App\Modules\Goods\Models;



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
                ComboSkuProductSku::updateOrCreate(['goods_sku_id' => $goods_sku->id, 'product_id' => $product_id], [
                    'goods_sku_id' => $goods_sku->id,
                    'product_id' => $product_id,
                    'product_sku_id' => $product_sku_id,
                ]);
            }
        }

        return $this;
    }
}