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
        // Kingspring流量计
        $goods = Goods::create(['code' => 'LLF10', 'name' => 'Kingspring流量计F10', 'type' => Goods::COMBO, 'category_id' => 77,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 28, 'quantity' => 2,]); // 指示扣
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 33, 'quantity' => 1,]); // 转子
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 39, 'quantity' => 1,]); // 导轨
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 48, 'quantity' => 1,]); // 视管
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 60, 'quantity' => 2,]); // 螺母
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 69, 'quantity' => 2,]); // 胶圈
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 78, 'quantity' => 1,]); // 盖子
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 86, 'quantity' => 1,]); // 接头
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-4LPM-SUS-A', 'size' => 'F10-PC视管-4LPM-SUS转子-外牙', 'model' => 'F-1004', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 94,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 175,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 405,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-4LPM-SUS-B', 'size' => 'F10-PC视管-4LPM-SUS转子-插管', 'model' => 'F-1004', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 94,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 175,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 399,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-4LPM-SUS-C', 'size' => 'F10-PC视管-4LPM-SUS转子-套管', 'model' => 'F-1004', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 94,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 175,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 402,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-4LPM-SUS-D', 'size' => 'F10-PC视管-4LPM-SUS转子-内牙', 'model' => 'F-1004', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 94,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 175,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 406,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-5LPM-SUS-A', 'size' => 'F10-PC视管-5LPM-SUS转子-外牙', 'model' => 'F-1005', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 95,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 180,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 405,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-5LPM-SUS-B', 'size' => 'F10-PC视管-5LPM-SUS转子-插管', 'model' => 'F-1005', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 95,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 180,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 399,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-5LPM-SUS-C', 'size' => 'F10-PC视管-5LPM-SUS转子-套管', 'model' => 'F-1005', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 95,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 180,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 402,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF10S10PC00B-5LPM-SUS-D', 'size' => 'F10-PC视管-5LPM-SUS转子-内牙', 'model' => 'F-1005', 'lowest_price' => 120, 'msrp' => 240,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 33, 'product_sku_id' => 95,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 39, 'product_sku_id' => 160,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 48, 'product_sku_id' => 180,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 60, 'product_sku_id' => 366,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 69, 'product_sku_id' => 376,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 78, 'product_sku_id' => 389,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 86, 'product_sku_id' => 406,]);

        $goods = Goods::create(['code' => 'LLF20', 'name' => 'Kingspring流量计F20', 'type' => Goods::COMBO, 'category_id' => 77,]);
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 28, 'quantity' => 2,]); // 指示扣
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 34, 'quantity' => 1,]); // 转子
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 40, 'quantity' => 1,]); // 导轨
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 49, 'quantity' => 1,]); // 视管
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 61, 'quantity' => 2,]); // 螺母
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 70, 'quantity' => 2,]); // 胶圈
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 80, 'quantity' => 1,]); // 盖子
        ComboProduct::create(['goods_id' => $goods->id, 'product_id' => 87, 'quantity' => 1,]); // 接头
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF20S20PC00B-10LPM-SUS-A', 'size' => 'F20-PC视管-10LPM-SUS转子-外牙', 'model' => 'F-2010', 'lowest_price' => 160, 'msrp' => 320,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 34, 'product_sku_id' => 104,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 40, 'product_sku_id' => 162,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 49, 'product_sku_id' => 197,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 61, 'product_sku_id' => 367,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 70, 'product_sku_id' => 378,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 80, 'product_sku_id' => 392,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 87, 'product_sku_id' => 412,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF20S20PC00B-10LPM-SUS-B', 'size' => 'F20-PC视管-10LPM-SUS转子-插管', 'model' => 'F-2010', 'lowest_price' => 160, 'msrp' => 320,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 34, 'product_sku_id' => 104,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 40, 'product_sku_id' => 162,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 49, 'product_sku_id' => 197,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 61, 'product_sku_id' => 367,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 70, 'product_sku_id' => 378,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 80, 'product_sku_id' => 392,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 87, 'product_sku_id' => 410,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF20S20PC00B-10LPM-SUS-C', 'size' => 'F20-PC视管-10LPM-SUS转子-套管', 'model' => 'F-2010', 'lowest_price' => 160, 'msrp' => 320,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 34, 'product_sku_id' => 104,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 40, 'product_sku_id' => 162,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 49, 'product_sku_id' => 197,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 61, 'product_sku_id' => 367,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 70, 'product_sku_id' => 378,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 80, 'product_sku_id' => 392,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 87, 'product_sku_id' => 416,]);
        $goods_sku = GoodsSku::create(['goods_id' => $goods->id, 'code' => 'LLF20S20PC00B-10LPM-SUS-D', 'size' => 'F20-PC视管-10LPM-SUS转子-内牙', 'model' => 'F-2010', 'lowest_price' => 160, 'msrp' => 320,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 28, 'product_sku_id' => 80,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 34, 'product_sku_id' => 104,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 40, 'product_sku_id' => 162,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 49, 'product_sku_id' => 197,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 61, 'product_sku_id' => 367,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 70, 'product_sku_id' => 378,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 80, 'product_sku_id' => 392,]);
        ComboSkuProductSku::create(['goods_sku_id' => $goods_sku->id, 'product_id' => 87, 'product_sku_id' => 413,]);
    }
}