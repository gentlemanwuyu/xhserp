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
    /**
     * product ID，用于与product表的关联
     *
     * @return mixed
     */
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

        $product_sku_ids = array_keys($skus);

        foreach ($this->skus as $ori_goods_sku) {
            // 如果SKU对应的产品SKU ID不在数组中，则将该SKU删除
            if (!in_array($ori_goods_sku->single_product_sku_id, $product_sku_ids)) {
                $ori_goods_sku->delete();
            }
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
                $goods_sku = GoodsSku::find($goods_sku_id);
                $goods_sku->update([
                    'code' => $item['code'],
                    'lowest_price' => $item['lowest_price'],
                    'msrp' => $item['msrp'],
                ]);
            }
        }

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