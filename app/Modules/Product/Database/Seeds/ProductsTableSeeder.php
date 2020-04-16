<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/2
 * Time: 14:08
 */

namespace App\Modules\Product\Database\Seeds;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductSku;
use App\Modules\Warehouse\Models\Inventory;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        // 压力表
        $p1 = Product::create(['code' => 'xhsylbsus304001', 'name' => '不锈钢径向压力表', 'category_id' => 2,]);
        $p1_s1 = ProductSku::create(['product_id' => $p1->id, 'code' => 'xhsylbsus304001-4kg', 'weight' => 68.2, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $p1_s1->id, 'product_id' => $p1_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p1_s2 = ProductSku::create(['product_id' => $p1->id, 'code' => 'xhsylbsus304001-7kg', 'weight' => 68.5, 'cost_price' => 15.8,]);
        Inventory::create(['sku_id' => $p1_s2->id, 'product_id' => $p1_s2->product_id, 'stock' => 105, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p1_s3 = ProductSku::create(['product_id' => $p1->id, 'code' => 'xhsylbsus304001-10kg', 'weight' => 70.8, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $p1_s3->id, 'product_id' => $p1_s3->product_id, 'stock' => 131, 'highest_stock' => 500, 'lowest_stock' => 50]);

        $p2 = Product::create(['code' => 'xhsylbsus304002', 'name' => '不锈钢轴向压力表', 'category_id' => 2,]);
        $p2_s1 = ProductSku::create(['product_id' => $p2->id, 'code' => 'xhsylbsus304002-4kg', 'weight' => 68.2, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $p2_s1->id, 'product_id' => $p2_s1->product_id, 'stock' => 32, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p2_s2 = ProductSku::create(['product_id' => $p2->id, 'code' => 'xhsylbsus304002-7kg', 'weight' => 68.5, 'cost_price' => 15.8,]);
        Inventory::create(['sku_id' => $p2_s2->id, 'product_id' => $p2_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p2_s3 = ProductSku::create(['product_id' => $p2->id, 'code' => 'xhsylbsus304002-10kg', 'weight' => 70.8, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $p2_s3->id, 'product_id' => $p2_s3->product_id, 'stock' => 48, 'highest_stock' => 500, 'lowest_stock' => 50]);

        $p3 = Product::create(['code' => 'xhsylbpp001', 'name' => 'PP单面隔膜压力表', 'category_id' => 3,]);
        $p3_s1 = ProductSku::create(['product_id' => $p3->id, 'code' => 'xhsylbpp001-4kg', 'weight' => 68.2, 'cost_price' => 28.4,]);
        Inventory::create(['sku_id' => $p3_s1->id, 'product_id' => $p3_s1->product_id, 'stock' => 12, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p3_s2 = ProductSku::create(['product_id' => $p3->id, 'code' => 'xhsylbpp001-7kg', 'weight' => 72.5, 'cost_price' => 30.8,]);
        Inventory::create(['sku_id' => $p3_s2->id, 'product_id' => $p3_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p3_s3 = ProductSku::create(['product_id' => $p3->id, 'code' => 'xhsylbpp001-10kg', 'weight' => 78.8, 'cost_price' => 132.8,]);
        Inventory::create(['sku_id' => $p3_s3->id, 'product_id' => $p3_s3->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        $p4 = Product::create(['code' => 'xhsylbpp002', 'name' => 'PP双面隔膜压力表', 'category_id' => 3,]);
        $p4_s1 = ProductSku::create(['product_id' => $p4->id, 'code' => 'xhsylbpp002-4kg', 'weight' => 78.2, 'cost_price' => 36.4,]);
        Inventory::create(['sku_id' => $p4_s1->id, 'product_id' => $p4_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p4_s2 = ProductSku::create(['product_id' => $p4->id, 'code' => 'xhsylbpp002-7kg', 'weight' => 88.5, 'cost_price' => 36.8,]);
        Inventory::create(['sku_id' => $p4_s2->id, 'product_id' => $p4_s2->product_id, 'stock' => 46, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p4_s3 = ProductSku::create(['product_id' => $p4->id, 'code' => 'xhsylbpp002-10kg', 'weight' => 93.8, 'cost_price' => 38.8,]);
        Inventory::create(['sku_id' => $p4_s3->id, 'product_id' => $p4_s3->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        $p5 = Product::create(['code' => 'xhsylbpp003', 'name' => '一体成型压力表', 'category_id' => 3,]);
        $p5_s1 = ProductSku::create(['product_id' => $p5->id, 'code' => 'xhsylbpp003-4kg', 'weight' => 78.2, 'cost_price' => 36.4,]);
        Inventory::create(['sku_id' => $p5_s1->id, 'product_id' => $p5_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p5_s2 = ProductSku::create(['product_id' => $p5->id, 'code' => 'xhsylbpp003-7kg', 'weight' => 88.5, 'cost_price' => 36.8,]);
        Inventory::create(['sku_id' => $p5_s2->id, 'product_id' => $p5_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $p5_s3 = ProductSku::create(['product_id' => $p5->id, 'code' => 'xhsylbpp003-10kg', 'weight' => 93.8, 'cost_price' => 38.8,]);
        Inventory::create(['sku_id' => $p5_s3->id, 'product_id' => $p5_s3->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        //液位开关
        $s1 = Product::create(['code' => 'xhsywkgpp011', 'name' => '立式液位开关011', 'category_id' => 6,]);
        $s1_s1 = ProductSku::create(['product_id' => $s1->id, 'code' => 'xhsywkgpp011-5m', 'weight' => 230.2, 'cost_price' => 10.4,]);
        Inventory::create(['sku_id' => $s1_s1->id, 'product_id' => $s1_s1->product_id, 'stock' => 80, 'highest_stock' => 500, 'lowest_stock' => 100]);
        $s1_s2 = ProductSku::create(['product_id' => $s1->id, 'code' => 'xhsywkgpp011-10m', 'weight' => 230.5, 'cost_price' => 11.8,]);
        Inventory::create(['sku_id' => $s1_s2->id, 'product_id' => $s1_s2->product_id, 'stock' => 56, 'highest_stock' => 500, 'lowest_stock' => 100]);
        $s1_s3 = ProductSku::create(['product_id' => $s1->id, 'code' => 'xhsywkgpp011-15m', 'weight' => 230.8, 'cost_price' => 18.8,]);
        Inventory::create(['sku_id' => $s1_s3->id, 'product_id' => $s1_s3->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 100]);

        // 滚轮片
        $d1 = Product::create(['code' => 'xhslppp124', 'name' => 'pp滚轮片124', 'category_id' => 35,]);
        $d1_s1 = ProductSku::create(['product_id' => $d1->id, 'code' => 'xhslppp124a', 'weight' => 25, 'cost_price' => 0.2,]);
        Inventory::create(['sku_id' => $d1_s1->id, 'product_id' => $d1_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $d1_s2 = ProductSku::create(['product_id' => $d1->id, 'code' => 'xhslppp124b', 'weight' => 25, 'cost_price' => 0.2,]);
        Inventory::create(['sku_id' => $d1_s2->id, 'product_id' => $d1_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $d1_s3 = ProductSku::create(['product_id' => $d1->id, 'code' => 'xhslppp124c', 'weight' => 25, 'cost_price' => 0.2,]);
        Inventory::create(['sku_id' => $d1_s3->id, 'product_id' => $d1_s3->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        $d2 = Product::create(['code' => 'xhslptpv162', 'name' => '孟山都滚轮片162', 'category_id' => 39,]);
        $d2_s1 = ProductSku::create(['product_id' => $d2->id, 'code' => 'xhslptpv162a', 'weight' => 25, 'cost_price' => 0.6,]);
        Inventory::create(['sku_id' => $d2_s1->id, 'product_id' => $d2_s1->product_id, 'stock' => 1248, 'highest_stock' => 5000, 'lowest_stock' => 2000]);
        $d2_s2 = ProductSku::create(['product_id' => $d2->id, 'code' => 'xhslptpv162b', 'weight' => 25, 'cost_price' => 0.6,]);
        Inventory::create(['sku_id' => $d2_s2->id, 'product_id' => $d2_s2->product_id, 'stock' => 1024, 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $d3 = Product::create(['code' => 'xhslpngwa173', 'name' => '硅胶滚轮片A173', 'category_id' => 40,]);
        $d3_s1 = ProductSku::create(['product_id' => $d3->id, 'code' => 'xhslpngwa173a', 'weight' => 25, 'cost_price' => 1.2,]);
        Inventory::create(['sku_id' => $d3_s1->id, 'product_id' => $d3_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $d3_s2 = ProductSku::create(['product_id' => $d3->id, 'code' => 'xhslpngwa173b', 'weight' => 25, 'cost_price' => 1.2,]);
        Inventory::create(['sku_id' => $d3_s2->id, 'product_id' => $d3_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        $d4 = Product::create(['code' => 'xhslpbj159', 'name' => '包胶滚轮片159', 'category_id' => 36,]);
        $d4_s1 = ProductSku::create(['product_id' => $d4->id, 'code' => 'xhslpbj159a', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $d4_s1->id, 'product_id' => $d4_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $d4_s2 = ProductSku::create(['product_id' => $d4->id, 'code' => 'xhslpbj159b', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $d4_s2->id, 'product_id' => $d4_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $d4_s3 = ProductSku::create(['product_id' => $d4->id, 'code' => 'xhslpbj159c', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $d4_s3->id, 'product_id' => $d4_s3->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        $d5 = Product::create(['code' => 'xhslptj144gj', 'name' => '包胶滚轮片144骨架', 'category_id' => 37,]);
        $d5_s1 = ProductSku::create(['product_id' => $d5->id, 'code' => 'xhslptj144gja', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $d5_s1->id, 'product_id' => $d5_s1->product_id, 'stock' => 2300, 'highest_stock' => 5000, 'lowest_stock' => 1000]);
        $d5_s2 = ProductSku::create(['product_id' => $d5->id, 'code' => 'xhslptj144gjb', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $d5_s2->id, 'product_id' => $d5_s2->product_id, 'stock' => 1500, 'highest_stock' => 5000, 'lowest_stock' => 1000]);
        $d5_s3 = ProductSku::create(['product_id' => $d5->id, 'code' => 'xhslptj144gjc', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $d5_s3->id, 'product_id' => $d5_s3->product_id, 'stock' => 786, 'highest_stock' => 5000, 'lowest_stock' => 1000]);

        // 胶圈
        $r1 = Product::create(['code' => 'xhslptj144jq', 'name' => '包胶滚轮片144胶圈', 'category_id' => 38,]);
        $r1_s1 = ProductSku::create(['product_id' => $r1->id, 'code' => 'xhslptj144jqpvc', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $r1_s1->id, 'product_id' => $r1_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 5000, 'lowest_stock' => 1000]);
        $r1_s2 = ProductSku::create(['product_id' => $r1->id, 'code' => 'xhslptj144jqtpv', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $r1_s2->id, 'product_id' => $r1_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 5000, 'lowest_stock' => 1000]);

        $r2 = Product::create(['code' => 'xhspz565jq', 'name' => '喷咀565胶圈', 'category_id' => 69,]);
        $r2_s1 = ProductSku::create(['product_id' => $r2->id, 'code' => 'xhspz565jqpvc', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $r2_s1->id, 'product_id' => $r2_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $r2_s2 = ProductSku::create(['product_id' => $r2->id, 'code' => 'xhspz565jqtpv', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $r2_s2->id, 'product_id' => $r2_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        $r3 = Product::create(['code' => 'xhsllj011jq', 'name' => '流量计胶圈', 'category_id' => 69,]);
        $r3_s1 = ProductSku::create(['product_id' => $r3->id, 'code' => 'xhspz565jqpvc', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $r3_s1->id, 'product_id' => $r3_s1->product_id, 'stock' => mt_rand(500, 5000), 'highest_stock' => 5000, 'lowest_stock' => 500]);
        $r3_s2 = ProductSku::create(['product_id' => $r3->id, 'code' => 'xhspz565jqtpv', 'weight' => 40, 'cost_price' => 0.25,]);
        Inventory::create(['sku_id' => $r3_s2->id, 'product_id' => $r3_s2->product_id, 'stock' => mt_rand(500, 5000), 'highest_stock' => 5000, 'lowest_stock' => 500]);

        // 喷咀
        $n1 = Product::create(['code' => 'xhspz565head', 'name' => '565喷咀头', 'category_id' => 54,]);
        $n1_s1 = ProductSku::create(['product_id' => $n1->id, 'code' => 'xhspz565heada', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $n1_s1->id, 'product_id' => $n1_s1->product_id, 'stock' => 36, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $n1_s2 = ProductSku::create(['product_id' => $n1->id, 'code' => 'xhspz565headb', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $n1_s2->id, 'product_id' => $n1_s2->product_id, 'stock' => mt_rand(500, 5000), 'highest_stock' => 5000, 'lowest_stock' => 500]);
        $n1_s3 = ProductSku::create(['product_id' => $n1->id, 'code' => 'xhspz565headc', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $n1_s3->id, 'product_id' => $n1_s3->product_id, 'stock' => mt_rand(500, 5000), 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $n2 = Product::create(['code' => 'xhspz565bottom', 'name' => '565喷咀底座', 'category_id' => 56,]);
        $n2_s1 = ProductSku::create(['product_id' => $n2->id, 'code' => 'xhspz565bottoma', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $n2_s1->id, 'product_id' => $n2_s1->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $n2_s2 = ProductSku::create(['product_id' => $n2->id, 'code' => 'xhspz565bottomb', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $n2_s2->id, 'product_id' => $n2_s2->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);
        $n2_s3 = ProductSku::create(['product_id' => $n2->id, 'code' => 'xhspz565bottomc', 'weight' => 40, 'cost_price' => 0.8,]);
        Inventory::create(['sku_id' => $n2_s3->id, 'product_id' => $n2_s3->product_id, 'stock' => mt_rand(50, 500), 'highest_stock' => 500, 'lowest_stock' => 50]);

        // 流量计
        $f1 = Product::create(['code' => 'xhsllj011sg', 'name' => '流量计011视管', 'category_id' => 15,]);
        $f1_s1 = ProductSku::create(['product_id' => $f1->id, 'code' => 'xhsllj011sgpc', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f1_s1->id, 'product_id' => $f1_s1->product_id, 'stock' => mt_rand(100, 1000), 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $f1_s2 = ProductSku::create(['product_id' => $f1->id, 'code' => 'xhsllj011sgpvc', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f1_s2->id, 'product_id' => $f1_s2->product_id, 'stock' => mt_rand(100, 1000), 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $f1_s3 = ProductSku::create(['product_id' => $f1->id, 'code' => 'xhsllj011sgpsu', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f1_s3->id, 'product_id' => $f1_s3->product_id, 'stock' => mt_rand(100, 1000), 'highest_stock' => 1000, 'lowest_stock' => 100]);

        $f2 = Product::create(['code' => 'xhsllj011dg', 'name' => '流量计011导轨', 'category_id' => 24,]);
        $f2_s1 = ProductSku::create(['product_id' => $f2->id, 'code' => 'xhsllj011dgsus316', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f2_s1->id, 'product_id' => $f2_s1->product_id, 'stock' => mt_rand(100, 1000), 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $f2_s2 = ProductSku::create(['product_id' => $f2->id, 'code' => 'xhsllj011dgtxb', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f2_s2->id, 'product_id' => $f2_s2->product_id, 'stock' => mt_rand(100, 1000), 'highest_stock' => 1000, 'lowest_stock' => 100]);

        $f3 = Product::create(['code' => 'xhsllj011zz', 'name' => '流量计011转子', 'category_id' => 20,]);
        $f3_s1 = ProductSku::create(['product_id' => $f3->id, 'code' => 'xhsllj011zzsus316', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f3_s1->id, 'product_id' => $f3_s1->product_id, 'stock' => mt_rand(100, 1000), 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $f3_s2 = ProductSku::create(['product_id' => $f3->id, 'code' => 'xhsllj011zztfl', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f3_s2->id, 'product_id' => $f3_s2->product_id, 'stock' => mt_rand(100, 1000), 'highest_stock' => 1000, 'lowest_stock' => 100]);

        $f4 = Product::create(['code' => 'xhsllj011lm', 'name' => '流量计011螺母', 'category_id' => 26,]);
        $f4_s1 = ProductSku::create(['product_id' => $f4->id, 'code' => 'xhsllj011lm316', 'weight' => 40, 'cost_price' => 6,]);
        Inventory::create(['sku_id' => $f4_s1->id, 'product_id' => $f4_s1->product_id, 'stock' => mt_rand(500, 2000), 'highest_stock' => 2000, 'lowest_stock' => 500]);

        $f5 = Product::create(['code' => 'xhsllj011jt', 'name' => '流量计011接头', 'category_id' => 27,]);
        $f5_s1 = ProductSku::create(['product_id' => $f5->id, 'code' => 'xhsllj011jtpvc', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f5_s1->id, 'product_id' => $f5_s1->product_id, 'stock' => 199, 'highest_stock' => 1000, 'lowest_stock' => 200]);
        $f5_s2 = ProductSku::create(['product_id' => $f5->id, 'code' => 'xhsllj011jtpp', 'weight' => 40, 'cost_price' => 25,]);
        Inventory::create(['sku_id' => $f5_s2->id, 'product_id' => $f5_s2->product_id, 'stock' => 182, 'highest_stock' => 1000, 'lowest_stock' => 200]);

        $f6 = Product::create(['code' => 'xhsllj011zsk', 'name' => '流量计011指示扣', 'category_id' => 30,]);
        $f6_s1 = ProductSku::create(['product_id' => $f6->id, 'code' => 'xhsllj011zsk001', 'weight' => 40, 'cost_price' => 0.5,]);
        Inventory::create(['sku_id' => $f6_s1->id, 'product_id' => $f6_s1->product_id, 'stock' => mt_rand(5000, 10000), 'highest_stock' => 10000, 'lowest_stock' => 5000]);
    }
}