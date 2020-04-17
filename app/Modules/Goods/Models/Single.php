<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 15:52
 */

namespace App\Modules\Goods\Models;

use App\Modules\Product\Models\Product;

class Single extends Goods
{
    public function getProductIdAttribute()
    {
        return SingleProduct::where('goods_id', $this->id)->value('product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 同步sku
     *
     * @param $skus
     * @return $this|bool
     */
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
                $goods_sku = GoodsSku::withTrashed()->find($goods_sku_id);
                $goods_sku->update([
                    'code' => $item['code'],
                    'lowest_price' => $item['lowest_price'],
                    'msrp' => $item['msrp'],
                    'deleted_at' => null,
                ]);
            }
        }

        GoodsSku::leftJoin('single_sku_product_skus AS ssps', 'ssps.goods_sku_id', '=', 'goods_skus.id')
            ->where('goods_skus.goods_id', $this->id)
            ->whereNotIn('ssps.product_sku_id', array_keys($skus))
            ->delete();

        return $this;
    }

    /**
     * 是否enable所有的sku
     *
     * @return bool
     */
    public function getAllEnabledAttribute()
    {
        foreach ($this->product->skus as $product_sku) {
            if (!$product_sku->single_sku) {
                return false;
            }
        }

        return true;
    }
}