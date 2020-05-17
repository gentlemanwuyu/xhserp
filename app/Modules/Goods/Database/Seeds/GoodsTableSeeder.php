<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 10:58
 */

namespace App\Modules\Goods\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Product\Models\Product;
use App\Modules\Goods\Models\Goods;
use App\Modules\Goods\Models\GoodsSku;
use App\Modules\Goods\Models\ComboProduct;
use App\Modules\Goods\Models\ComboSkuProductSku;
use App\Modules\Goods\Models\SingleProduct;
use App\Modules\Goods\Models\SingleSkuProductSku;

class GoodsTableSeeder extends Seeder
{
    public function run()
    {
        $this->singleSeeder();
        $this->comboSeeder();
    }

    /**
     * single商品数据填充
     */
    public function singleSeeder()
    {
        $products = Product::whereIn('id', [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111])->get();
        $category_relations = [
            1 => 69,
            2 => 69,
            3 => 70,
            4 => 70,
            5 => 70,
            6 => 70,
            7 => 70,
            8 => 72,
            9 => 72,
            10 => 72,
            11 => 72,
            12 => 72,
            13 => 72,
            14 => 72,
            15 => 72,
            16 => 73,
            17 => 73,
            18 => 73,
            19 => 73,
            20 => 73,
            21 => 73,
            22 => 73,
            23 => 73,
            24 => 72,
            25 => 72,
            26 => 72,
            27 => 72,
            96 => 80,
            97 => 80,
            98 => 80,
            99 => 80,
            100 => 80,
            101 => 80,
            102 => 80,
            103 => 80,
            104 => 80,
            105 => 80,
            106 => 81,
            107 => 81,
            108 => 81,
            109 => 81,
            110 => 81,
            111 => 81,
        ];
        foreach ($products as $product) {
            $goods = Goods::create([
                'code' => $product->code,
                'name' => $product->name,
                'type' => Goods::SINGLE,
                'category_id' => $category_relations[$product->id],
            ]);

            SingleProduct::create([
                'goods_id' => $goods->id,
                'product_id' => $product->id,
            ]);

            foreach ($product->skus as $product_sku) {
                $goods_sku = GoodsSku::create([
                    'goods_id' => $goods->id,
                    'code' => $product_sku->code,
                    'size' => $product_sku->size,
                    'model' => $product_sku->model,
                    'lowest_price' => $product_sku->cost_price * 2,
                    'msrp' => $product_sku->cost_price * 4,
                ]);
                SingleSkuProductSku::create([
                    'goods_sku_id' => $goods_sku->id,
                    'product_sku_id' => $product_sku->id,
                ]);
            }
        }
    }

    public function comboSeeder()
    {
        // 滚轮片144
        $goods = Goods::create(['code' => 'xhsglpbj144', 'name' => '包胶滚轮片144', 'type' => Goods::COMBO, 'category_id' => 92,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 11, 'quantity' => 1,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 12, 'quantity' => 1,]);
        $goods_sku1 = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'xhsglpbj144a', 'lowest_price' => 0.6, 'msrp' => 1.2,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 11, 'product_sku_id' => 29,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 12, 'product_sku_id' => 32,]);
        $goods_sku2 = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'xhsglpbj144b', 'lowest_price' => 0.6, 'msrp' => 1.2,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 11, 'product_sku_id' => 30,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 12, 'product_sku_id' => 32,]);
        $goods_sku3 = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'xhsglpbj144c', 'lowest_price' => 0.6, 'msrp' => 1.2,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 11, 'product_sku_id' => 31,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 12, 'product_sku_id' => 33,]);

        // 流量计011
        $goods = Goods::create(['code' => 'xhsllj011', 'name' => 'kingspring流量计011', 'type' => Goods::COMBO, 'category_id' => 89,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 17, 'quantity' => 1,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 18, 'quantity' => 1,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 19, 'quantity' => 1,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 20, 'quantity' => 2,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 21, 'quantity' => 2,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 22, 'quantity' => 2,]);
        $goods_sku1 = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'xhsgllj01110-100lpm', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 17, 'product_sku_id' => 44,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 18, 'product_sku_id' => 47,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 19, 'product_sku_id' => 49,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 20, 'product_sku_id' => 51,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 21, 'product_sku_id' => 52,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku1->id, 'product_id' => 22, 'product_sku_id' => 54,]);
        $goods_sku2 = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'xhsgllj01115-150lpm', 'lowest_price' => 130, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 17, 'product_sku_id' => 45,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 18, 'product_sku_id' => 47,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 19, 'product_sku_id' => 49,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 20, 'product_sku_id' => 51,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 21, 'product_sku_id' => 52,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku2->id, 'product_id' => 22, 'product_sku_id' => 54,]);
        $goods_sku3 = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'xhsgllj01120-200lpm', 'lowest_price' => 140, 'msrp' => 260,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 17, 'product_sku_id' => 46,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 18, 'product_sku_id' => 48,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 19, 'product_sku_id' => 50,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 20, 'product_sku_id' => 51,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 21, 'product_sku_id' => 52,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku3->id, 'product_id' => 22, 'product_sku_id' => 54,]);
    }
}