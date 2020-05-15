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
        $product = Product::create(['code' => 'YL0001', 'name' => '不锈钢径向压力表001', 'category_id' => 2,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-10KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-10KG', 'weight' => 181, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 10, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-15KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-15KG', 'weight' => 181, 'cost_price' => 15.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-25KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-25KG', 'weight' => 181, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-2KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-2KG', 'weight' => 181, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 11, 'highest_stock' => 50, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-35KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-35KG', 'weight' => 181, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-4KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-4KG', 'weight' => 181, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 16, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-4KG-nhs', 'size' => '2.5"表径1/4"外牙', 'model' => '0-4KG', 'weight' => 181, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 4, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-7KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-7KG', 'weight' => 181, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0001A-SUS00B-7KG-sk', 'size' => '', 'model' => '', 'weight' => 181, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 16, 'highest_stock' => 20, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'YL0002', 'name' => '不锈钢轴向压力表002', 'category_id' => 2,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0002A-SUS00B-15KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-15KG', 'weight' => 211, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 6, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0002A-SUS00B-35KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-35KG', 'weight' => 211, 'cost_price' => 15.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0002A-SUS00B-4KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-4KG', 'weight' => 211, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0002A-SUS00B-7KG', 'size' => '2.5"表径1/4"外牙', 'model' => '0-7KG', 'weight' => 211, 'cost_price' => 16.8,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 28, 'highest_stock' => 30, 'lowest_stock' => 10]);

        $product = Product::create(['code' => 'YL0003', 'name' => 'PP单面不带座压力表003', 'category_id' => 3,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0003A-10KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-10KG', 'weight' =>220, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 8, 'highest_stock' => 10, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0003A-1KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-1KG', 'weight' =>220, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 10, 'highest_stock' => 10, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0003A-2KGPP02B', 'size' => '2.5"表径1/4"外牙', 'model' => '0-2KG', 'weight' =>220, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 37, 'highest_stock' => 50, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0003A-4KGPP02B', 'size' => '2016年12月7日库存表（盘点后）xls', 'model' => '0-4KG', 'weight' =>220, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0003A-7KGPP02B', 'size' => '2.5"表径1/4"外牙', 'model' => '0-7KG', 'weight' =>220, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'YL0004', 'name' => 'PP双面不带座压力表004', 'category_id' => 3,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0004A-10KGPP02B', 'size' => '2.5"表径1/4"外牙', 'model' => '0-10KG', 'weight' =>291, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 5, 'highest_stock' => 10, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0004A-2KGPP02B', 'size' => '2.5"表径1/4"外牙', 'model' => '0-2KG', 'weight' =>291, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 40, 'highest_stock' => 10, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0004A-4KGPP02B', 'size' => '2.5"表径1/4"外牙', 'model' => '0-2KG', 'weight' =>291, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 48, 'highest_stock' => 50, 'lowest_stock' => 2]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0004A-7KGPP02B', 'size' => '2.5"表径1/4"外牙', 'model' => '0-2KG', 'weight' =>291, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 34, 'highest_stock' => 50, 'lowest_stock' => 2]);

        $product = Product::create(['code' => 'YL0005', 'name' => 'PP单面隔膜压力表005', 'category_id' => 3,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0005A-10KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-10KG', 'weight' =>318, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 30, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0005A-1KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-1KG', 'weight' =>318, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 30, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0005A-2KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-2KG', 'weight' =>318, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 185, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0005A-4KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-4KG', 'weight' =>318, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 61, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0005A-7KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-7KG', 'weight' =>318, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 10]);

        $product = Product::create(['code' => 'YL0006', 'name' => 'PP双面隔膜压力表006', 'category_id' => 3,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0006A-10KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-10KG', 'weight' =>392, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 19, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0006A-1KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-10KG', 'weight' =>392, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 4, 'highest_stock' => 30, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0006A-2KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-2KG', 'weight' =>392, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 10, 'highest_stock' => 50, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0006A-4KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-4KG', 'weight' =>392, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 75, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0006A-6KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-6KG', 'weight' =>392, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 33, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'YL0007', 'name' => 'PP一体成型隔膜压力表007', 'category_id' => 3,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0007A-10KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-10KG', 'weight' =>269, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 37, 'highest_stock' => 50, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0007A-2KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-2KG', 'weight' =>269, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 44, 'highest_stock' => 50, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0007A-4KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-4KG', 'weight' =>269, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 30, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0007A-4KGPP02B-PTFE', 'size' => '2.5"表径1/2"内牙', 'model' => '0-4KG', 'weight' =>269, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 15, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YL0007A-7KGPP02B', 'size' => '2.5"表径1/2"内牙', 'model' => '0-7KG', 'weight' =>269, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 10, 'highest_stock' => 80, 'lowest_stock' => 10]);

        // 液位开关
        $product = Product::create(['code' => 'YW0011', 'name' => 'PP立式液位开关011', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0011APP01B', 'size' => '线长1.5米', 'model' => 'LSP43B', 'weight' =>21.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0011APP25B', 'size' => '线长4米', 'model' => 'LSP43B', 'weight' =>25, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0011APP25B', 'size' => '标准线', 'model' => 'LSP43B', 'weight' =>15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 20, 'highest_stock' => 300, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0012', 'name' => 'PP立式液位开关012', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0012APP00Y2', 'size' => '线长1.5米、透明浮球', 'model' => 'LSP40A', 'weight' =>18.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0012APP01Y1', 'size' => '黄色标准线、螺母', 'model' => 'LSP40A', 'weight' =>12, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 250, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0012APP01Y3', 'size' => '线长1.5米、黑色', 'model' => 'LSP40A', 'weight' =>18.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0012APP02B', 'size' => '标准线黑线', 'model' => 'LSP40A', 'weight' =>12.1, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 155, 'highest_stock' => 250, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0013', 'name' => 'PP立式液位开关013', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0013APP30Y1', 'size' => '白色浮球、线红', 'model' => 'LSP52', 'weight' =>15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0013APP30Y1', 'size' => '红线.蓝色浮球', 'model' => 'LSP52', 'weight' =>15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0013APP35B', 'size' => '蓝色浮球', 'model' => 'LSP52', 'weight' =>15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 27, 'highest_stock' => 100, 'lowest_stock' => 20]);

        $product = Product::create(['code' => 'YW0014', 'name' => 'PP立式液位开关014', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0014APP00B', 'size' => '红线白色浮杆、浮球', 'model' => 'LSP45A', 'weight' =>18, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0014APP00B', 'size' => '黑色浮杆、白色螺母', 'model' => 'LSP45A', 'weight' =>18, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0014APP00B', 'size' => '黑色浮杆、白色螺母、灰色线', 'model' => 'LSP45A', 'weight' =>18, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0014APP00B', 'size' => '白色浮杆、透明浮球、黄色线', 'model' => 'LSP45A', 'weight' =>18, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0014BPP00B', 'size' => '白色浮球、黄色线', 'model' => 'LSP45A', 'weight' =>12.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0014BPP00B', 'size' => '透明浮球、红色线', 'model' => 'LSP45A', 'weight' =>12.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 43, 'highest_stock' => 300, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0015', 'name' => 'PP立式液位开关015', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0015APP01B', 'size' => '不标准黑线', 'model' => 'LSP25', 'weight' =>6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 50, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0015APP00B', 'size' => '黄短线', 'model' => 'LSP25', 'weight' =>6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 258, 'highest_stock' => 100, 'lowest_stock' => 10]);

        $product = Product::create(['code' => 'YW0016', 'name' => '不锈钢立式液位开关016', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0016APP01B', 'size' => 'SUS导杆，PP浮球', 'model' => 'LSSP1045', 'weight' =>38, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 26, 'highest_stock' => 50, 'lowest_stock' => 10]);

        $product = Product::create(['code' => 'YW0017', 'name' => '不锈钢立式液位开关017', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0017ASUS30400B', 'size' => '304黑线', 'model' => 'LSS1-A1', 'weight' =>42, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 6, 'highest_stock' => 70, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0017ASUS30400B（粗牙）', 'size' => '304黑线', 'model' => 'LSS1-A1', 'weight' =>42, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0017ASUS31600B', 'size' => '银白线', 'model' => 'LSS1-A1', 'weight' =>47, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 13, 'highest_stock' => 50, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0018', 'name' => '不锈钢立式液位开关018', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0018ASUS31600B', 'size' => '', 'model' => 'LSS1-A1', 'weight' =>135, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2, 'highest_stock' => 0, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0019', 'name' => 'PP卧式液位开关019', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0019APP02B', 'size' => '', 'model' => 'YZ-I', 'weight' =>19, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 60, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0020', 'name' => 'PP卧式液位开关020', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0020APP02B', 'size' => '', 'model' => 'YZ-II', 'weight' =>21, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 13, 'highest_stock' => 60, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0021', 'name' => 'PP卧式液位开关021', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0021APP02B', 'size' => '', 'model' => 'YZ-III', 'weight' =>29, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 25, 'highest_stock' => 60, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0022', 'name' => 'PP卧式液位开关022', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0022APP02B', 'size' => '白色胶圈', 'model' => 'YZ-IV', 'weight' =>18, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 37, 'highest_stock' => 60, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0023', 'name' => '不锈钢卧式液位开关023', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0023ASUS00B', 'size' => '304', 'model' => 'LSS2-A1', 'weight' =>51, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1, 'highest_stock' => 30, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0023ASUS00B', 'size' => '316', 'model' => 'LSS2-A1', 'weight' =>65, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 30, 'lowest_stock' => 1]);

        $product = Product::create(['code' => 'YW0024', 'name' => '不锈钢卧式液位开关024', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0024ASUS00B', 'size' => '316', 'model' => 'LSS2-A2', 'weight' =>191, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 30, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0025', 'name' => '不锈钢卧式液位开关025', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0025ASUS00B', 'size' => '', 'model' => 'LSSYZ-2H', 'weight' =>38, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 10, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0026', 'name' => '多点式液位开关026', 'category_id' => 6,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0026ASUS00B', 'size' => '', 'model' => 'LS1A1-250-2', 'weight' =>85, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1, 'highest_stock' => 0, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0026BPP01B', 'size' => '', 'model' => 'LSP-240-2', 'weight' =>37.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 60, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0027', 'name' => 'PP立式液位开关027', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0027APP00B', 'size' => '弧形、蓝色浮球', 'model' => 'LSP40-2617', 'weight' =>12, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 50, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0027APP00B', 'size' => '弧形、透明浮球', 'model' => 'LSP40-2617', 'weight' =>12, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 29, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0027APP00B', 'size' => '弧形、白色浮球', 'model' => 'LSP40-2617', 'weight' =>12, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 31, 'highest_stock' => 100, 'lowest_stock' => 20]);

        $product = Product::create(['code' => 'YW0028', 'name' => 'PP立式液位开关028', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0028APP00Y1', 'size' => '黄长线，1.5米 线', 'model' => 'LSP43', 'weight' =>13.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 25, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0028APP25B', 'size' => '白色浮子、短黄线', 'model' => 'LSP43', 'weight' =>12.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 175, 'highest_stock' => 300, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0028BPVDF00B', 'size' => '配件浮球 长线', 'model' => 'LSP43', 'weight' =>28, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 9, 'highest_stock' => 50, 'lowest_stock' => 5]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0028BPVDF02B', 'size' => '白色浮子、白色标杆、短黑线', 'model' => 'LSP43', 'weight' =>23.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'YW0029', 'name' => 'PP立式液位开关029', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0029APP01B', 'size' => '黄线', 'model' => 'LSP34', 'weight' =>10, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 55, 'highest_stock' => 200, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'YW0030', 'name' => 'PP立式液位开关030', 'category_id' => 5,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'YW0030APP25B', 'size' => '', 'model' => 'LSP43-3520', 'weight' =>16, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 40, 'highest_stock' => 100, 'lowest_stock' => 50]);

        // 流量计配件
        $product = Product::create(['code' => 'LLBUC', 'name' => '流量计指示扣', 'category_id' => 13,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLBUC15B', 'size' => '15B', 'model' => '', 'weight' =>2, 'cost_price' => 0.05,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 9248, 'highest_stock' => 20000, 'lowest_stock' => 2000]);

        $product = Product::create(['code' => 'LLF10ZA', 'name' => 'F10空气转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZBPVC85B', 'size' => 'PVC、1.0LPM', 'model' => 'F-10Z', 'weight' =>0.23, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3, 'highest_stock' => 100, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZBPVC85B', 'size' => 'PVC、4.0LPM', 'model' => 'F-10Z', 'weight' =>0.94, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 42, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZCPVC85B', 'size' => 'PVC、5.0LPM', 'model' => 'F-10Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZDPVC00B', 'size' => 'PVC、8.0LPM', 'model' => 'F-10Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 4, 'highest_stock' => 100, 'lowest_stock' => 20]);

        $product = Product::create(['code' => 'LLF20ZA', 'name' => 'F20空气转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZDPVC85B(A20)', 'size' => 'PVC、30LPM 空氣', 'model' => 'F-20Z', 'weight' =>0.19, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 117, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZFPVC85B(A20)', 'size' => 'PVC、50LPM', 'model' => 'F-20Z', 'weight' =>0.36, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 158, 'highest_stock' => 500, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZCPVC85B(A20)', 'size' => 'PVC、100NLPM', 'model' => 'F-20Z', 'weight' =>0.45, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 146, 'highest_stock' => 500, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20ZDSUS00B（F20)', 'size' => 'SUS、500NLPM', 'model' => 'FAIR-20Z', 'weight' =>14.91, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 5, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20ZESUS00B（F20)', 'size' => 'SUS、1000NLPM', 'model' => 'FAIR-20Z', 'weight' =>23.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 61, 'highest_stock' => 200, 'lowest_stock' => 20]);

        $product = Product::create(['code' => 'LLF30ZA', 'name' => 'F30空气转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30ZPVC85B(F30)', 'size' => 'PVC、400NLPM', 'model' => 'F-30400', 'weight' =>3.14, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 240, 'highest_stock' => 500, 'lowest_stock' => 56]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30ZPVC85B(F30)', 'size' => 'PVC、500NLPM', 'model' => 'F-30500', 'weight' =>3.34, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 114, 'highest_stock' => 500, 'lowest_stock' => 92]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30ZCSUS00B(F30)', 'size' => 'SUS、1000NLPM', 'model' => 'FAIR-30Z', 'weight' =>21.22, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 237, 'highest_stock' => 200, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF45ZA', 'name' => 'F45空气转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZBPVC85B', 'size' => 'PVC、150NLPM', 'model' => 'F-45/60Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3, 'highest_stock' => 50, 'lowest_stock' => 1]);

        $product = Product::create(['code' => 'LLF10ZW', 'name' => 'F10转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZBSUS00B', 'size' => 'SUS、4.0LPM', 'model' => 'F-10Z', 'weight' =>1.13, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 291, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZCSUS00B', 'size' => 'SUS、5.0LPM', 'model' => 'F-10Z', 'weight' =>1.52, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 48, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZDSUS00B', 'size' => 'SUS、8.0LPM', 'model' => 'F-10Z', 'weight' =>3.38, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 68, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZDTI00B', 'size' => 'TI、8.0LPM', 'model' => 'F-10Z', 'weight' =>2.2, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 94, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZESUS00B', 'size' => 'SUS、10LPM', 'model' => 'F-10Z', 'weight' =>4.37, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1892, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZETI00B', 'size' => 'TI、10LPM', 'model' => 'F-10Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 29, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZFSUS00B', 'size' => 'SUS、15LPM', 'model' => 'F-10Z', 'weight' =>4.97, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 381, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZFTI00B', 'size' => 'TI15LPM', 'model' => 'F-10Z', 'weight' =>4.33, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10ZGSUS00B', 'size' => 'SUS、20LPM', 'model' => 'F-10Z', 'weight' =>6.56, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 726, 'highest_stock' => 1500, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF20ZW', 'name' => 'F20转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZBPTFE01B', 'size' => 'PTFE、10LPM', 'model' => 'F-20Z', 'weight' =>5.43, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 56, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZBSUS00B', 'size' => 'SUS、10LPM', 'model' => 'F-20Z', 'weight' =>7.02, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 36, 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZCPTFE01B', 'size' => 'PTFE、20LPM', 'model' => 'F-20Z', 'weight' =>6.92, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 345, 'highest_stock' => 500, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZCSUS00B', 'size' => 'SUS、20LPM', 'model' => 'F-20Z', 'weight' =>12.7, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 125, 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZDPTFE00B', 'size' => 'PTFE、30LPM', 'model' => 'F-20Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 136, 'highest_stock' => 0, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZDSUS00B', 'size' => 'SUS、30LPM', 'model' => 'F-20Z', 'weight' =>18.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 27, 'highest_stock' => 1000, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZDSUS00B(汉生加强型专用）', 'size' => 'SUS、30LPM 汉生', 'model' => 'J-20Z', 'weight' =>18.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 375, 'highest_stock' => 1000, 'lowest_stock' => 120]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZDTI00B', 'size' => 'TI、30LPM', 'model' => 'F-20Z', 'weight' =>13.7, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 17, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZESUS00B', 'size' => 'SUS、40LPM', 'model' => 'F-20Z', 'weight' =>20.04, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 952, 'highest_stock' => 1000, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZETI00B', 'size' => 'TI、40LPM', 'model' => 'F-20Z', 'weight' =>16.02, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 8, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20ZFSUS00B', 'size' => 'SUS、50LPM', 'model' => 'F-20Z', 'weight' =>29.01, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 107, 'highest_stock' => 300, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF30ZW', 'name' => 'F30转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZAPTFE01B', 'size' => 'PTFE、20LPM', 'model' => 'F-30Z', 'weight' =>6.14, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 84, 'highest_stock' => 30, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZASUS00B', 'size' => 'SUS、20LPM', 'model' => 'F-30Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 52, 'highest_stock' => 30, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZBPTFE01B', 'size' => 'PTFE、30LPM', 'model' => 'F-30Z', 'weight' =>10.22, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 80, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZBSUS00B', 'size' => 'SUS、30LPM', 'model' => 'F-30Z', 'weight' =>20, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 43, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZCPTFE01B', 'size' => 'PTFE、40LPM', 'model' => 'F-30Z', 'weight' =>14.63, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 70, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZCSUS00B', 'size' => 'SUS、40LPM', 'model' => 'F-30Z', 'weight' =>23.26, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 658, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZDPTFE01B', 'size' => 'PTFE、50LPM', 'model' => 'F-30Z', 'weight' =>14.89, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 60, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZDSUS00B', 'size' => 'SUS、50LPM', 'model' => 'F-30Z', 'weight' =>28.51, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 161, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZEPTFE01B', 'size' => 'PTFE、60LPM', 'model' => 'F-30Z', 'weight' =>16.59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 112, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZESUS00B', 'size' => 'SUS、60LPM', 'model' => 'F-30Z', 'weight' =>35.35, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 86, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZETI00B', 'size' => 'TI、60LPM', 'model' => 'F-30Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZFSUS00B', 'size' => 'SUS、70LPM', 'model' => 'F-30Z', 'weight' =>39.82, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 45, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZFTI00B', 'size' => 'TI、70LPM', 'model' => 'F-30Z', 'weight' =>25.87, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 20, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZGSUS00B', 'size' => 'SUS、80LPM', 'model' => 'F-30Z', 'weight' =>43.37, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 55, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZGTI00B', 'size' => 'TI、80LPM', 'model' => 'F-30Z', 'weight' =>30, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZHSUS00B', 'size' => 'SUS、90LPM', 'model' => 'F-30Z', 'weight' =>49.99, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 153, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZISUS00B', 'size' => 'SUS、100LPM', 'model' => 'F-30Z', 'weight' =>54.95, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30ZITI00B', 'size' => 'TI、100LPM', 'model' => 'F-30Z', 'weight' =>32.04, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 37, 'highest_stock' => 100, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF31ZW', 'name' => 'F31转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZASUS00B', 'size' => 'SUS、20LPM', 'model' => 'F-31Z', 'weight' =>7.66, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 19, 'highest_stock' => 300, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZBSUS00B', 'size' => 'SUS、30LPM', 'model' => 'F-31Z', 'weight' =>14.37, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 37, 'highest_stock' => 300, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZCSUS00B', 'size' => 'SUS、40LPM', 'model' => 'F-31Z', 'weight' =>19.25, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 47, 'highest_stock' => 300, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZDSUS00B', 'size' => 'SUS、50LPM', 'model' => 'F-31Z', 'weight' =>24.34, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 145, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZESUS00B', 'size' => 'SUS、60LPM', 'model' => 'F-31Z', 'weight' =>26.94, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 6, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZFSUS00B', 'size' => 'SUS、70LPM', 'model' => 'F-31Z', 'weight' =>36.32, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 70, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZGSUS00B', 'size' => 'SUS、80LPM', 'model' => 'F-31Z', 'weight' =>39.26, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 124, 'highest_stock' => 300, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZHSUS00B', 'size' => 'SUS、90LPM', 'model' => 'F-31Z', 'weight' =>41.59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 21, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZIPTFE00B', 'size' => 'PTFE、100LPM', 'model' => 'F-31Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 17, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31ZISUS00B', 'size' => 'SUS、100LPM', 'model' => 'F-31Z', 'weight' =>46.72, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 300, 'lowest_stock' => 20]);

        $product = Product::create(['code' => 'LLF32ZW', 'name' => 'F32转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S15PVC85B', 'size' => 'F32、15LPM', 'model' => 'F-32', 'weight' =>16, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 226, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S65CU10B', 'size' => 'F32、65LPM', 'model' => 'F-32', 'weight' =>29.59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 56, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S110CU10B', 'size' => 'F32、110LPM', 'model' => 'F-32', 'weight' =>55, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 76, 'highest_stock' => 500, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'LLF45ZW', 'name' => 'F45转子', 'category_id' => 9,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZAPTFE01B', 'size' => 'PTFE、100LPM', 'model' => 'F-45/60Z', 'weight' =>175, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 17, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZASUS00B', 'size' => 'SUS、100LPM', 'model' => 'F-45/60Z', 'weight' =>178.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 101, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZBPTFE01B', 'size' => 'PTFE、150LPM', 'model' => 'F-45/60Z', 'weight' =>128.4, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZBSUS00B', 'size' => 'SUS、150LPM', 'model' => 'F-45/60Z', 'weight' =>327.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 32, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZCPTFE01B', 'size' => 'PTFE、200LPM', 'model' => 'F-45/60Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 71, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZCSUS00B', 'size' => 'SUS、200LPM', 'model' => 'F-45/60Z', 'weight' =>362, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 57, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZDPTFE01B', 'size' => 'PTFE、250LPM', 'model' => 'F-45/60Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 158, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZDPVDF00B', 'size' => 'PVDF、250LPM', 'model' => 'F-45/60Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZDSUS00B', 'size' => 'SUS、250LPM', 'model' => 'F-45/60Z', 'weight' =>451.7, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 60, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZDSUS00B', 'size' => 'SUS、300LPM', 'model' => 'F-45/60Z', 'weight' =>495.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 13, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZESUS00B', 'size' => 'SUS、350LPM', 'model' => 'F-45/60Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZFSUS00B', 'size' => 'SUS、450LPM', 'model' => 'F-45/60Z', 'weight' =>457.7, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 208, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZGSUS00B', 'size' => 'SUS、500LPM', 'model' => 'F-45/60Z', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45ZGSUS00B', 'size' => 'SUS、600LPM', 'model' => 'F-45/60Z', 'weight' =>622.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 53, 'highest_stock' => 200, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'LLF10DG', 'name' => 'F10导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10DACRP02B', 'size' => '￠1.6*95L', 'model' => 'F-10D', 'weight' =>0.26, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 953, 'highest_stock' => 2000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10DSUS00B', 'size' => '￠1.6*95L', 'model' => 'F-10D', 'weight' =>1.4, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLF20DG', 'name' => 'F20导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20DACRP02B', 'size' => '￠1.6*111L', 'model' => 'F-20D', 'weight' =>0.29, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 415, 'highest_stock' => 1000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20DSUS00B', 'size' => '￠1.6*111L', 'model' => 'F-20D', 'weight' =>1.67, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 119, 'highest_stock' => 1000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLF30DG', 'name' => 'F30导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LL碳纤导轨F30', 'size' => '￠3*176L', 'model' => 'F-30D', 'weight' =>1.84, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 461, 'highest_stock' => 2000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30SUS00B导轨', 'size' => '￠3*176L', 'model' => 'F-30D', 'weight' =>10.1, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 709, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLF31DG', 'name' => 'F31导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31DACRP02B', 'size' => '￠3*119L', 'model' => 'F-31D', 'weight' =>1.28, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31DASUS00B', 'size' => '￠3*119L', 'model' => 'F-31D', 'weight' =>6.84, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 357, 'highest_stock' => 800, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF45DG', 'name' => 'F45导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LL碳纤导轨F45', 'size' => '￠7*237L(￠7*218L)', 'model' => 'F-45D', 'weight' =>12.14, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 290, 'highest_stock' => 1500, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45DASUS00B', 'size' => '￠7*237L', 'model' => 'F-45D', 'weight' =>64.2, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 108, 'highest_stock' => 1000, 'lowest_stock' => 100]);

        $product = Product::create(['code' => 'LLJ15DG', 'name' => 'J15导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15SUS00B', 'size' => '￠1.6*105L', 'model' => 'J-15D', 'weight' =>1.56, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 201, 'highest_stock' => 2000, 'lowest_stock' => 100]);

        $product = Product::create(['code' => 'LLJ20DG', 'name' => 'J20导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20SUS00B', 'size' => '￠1.6*110L', 'model' => 'J-20D', 'weight' =>1.91, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 138, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ25DG', 'name' => 'J25导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25SUS00B', 'size' => '￠3*130L', 'model' => 'J-25D', 'weight' =>8.24, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 398, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ50DG', 'name' => 'J50导轨', 'category_id' => 10,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ50SUS00B', 'size' => '￠7*237L', 'model' => 'J-50D', 'weight' =>71.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 96, 'highest_stock' => 1000, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'LLF10SG', 'name' => 'F10视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S1PC00B', 'size' => 'F10、1.0LPM', 'model' => 'F-1001', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 53, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S1PSU00B', 'size' => 'F10、1.0LPM', 'model' => 'F-1001', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 11, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S4PC00B', 'size' => 'F10、4.0LPM', 'model' => 'F-1004', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S4PC00B', 'size' => 'F10、4.0LPM', 'model' => 'F-1004', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 42, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S4PSU00B', 'size' => 'F10、4.0LPM', 'model' => 'F-1004', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 19, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S4PVC00B', 'size' => 'F10、4.0LPM', 'model' => 'F-1004', 'weight' =>33.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 9, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S5PC00B', 'size' => 'F10、5.0LPM', 'model' => 'F-1005', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 18, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S5PSU00B', 'size' => 'F10、5.0LPM', 'model' => 'F-1005', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S5PSU00B', 'size' => 'F10、5.0LPM', 'model' => 'F-1005', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 44, 'highest_stock' => 100, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S5PVC00B', 'size' => 'F10、5.0LPM', 'model' => 'F-1005', 'weight' =>33.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S8PC00B', 'size' => 'F10、8.0LPM', 'model' => 'F-1008', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 19, 'highest_stock' => 250, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S8PSU00B', 'size' => 'F10、8.0LPM', 'model' => 'F-1008', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S8PVC00B', 'size' => 'F10、8.0LPM', 'model' => 'F-1008', 'weight' =>33.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PC00B', 'size' => 'F10、10LPM', 'model' => 'F-1010', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 9, 'highest_stock' => 250, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PSU00B', 'size' => 'F10、10LPM', 'model' => 'F-1010', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 103, 'highest_stock' => 100, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PVC00B', 'size' => 'F10、10LPM', 'model' => 'F-1010', 'weight' =>33.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 103, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S15PC00B', 'size' => 'F10、15LPM', 'model' => 'F-1015', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 48, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S15PSU00B', 'size' => 'F10、15LPM', 'model' => 'F-1015', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 14, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S15PVC00B', 'size' => 'F10、15LPM', 'model' => 'F-1015', 'weight' =>33.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S15PVC00B', 'size' => 'F10、15LPM', 'model' => 'F-1015', 'weight' =>33.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 43, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PC00B', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>29.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 191, 'highest_stock' => 500, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PSU00B', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>36.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 57, 'highest_stock' => 100, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PVC00B', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>33.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 8, 'highest_stock' => 100, 'lowest_stock' => 30]);


        $product = Product::create(['code' => 'LLF20SG', 'name' => 'F20视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S5PC00B', 'size' => 'F20、5.0LPM', 'model' => 'F-2005', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 43, 'highest_stock' => 100, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S10PC00B', 'size' => 'F20、10LPM', 'model' => 'F-2010', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 288, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S10PC00B', 'size' => 'F20、10LPM', 'model' => 'F-2010', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 77, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S10PP00B', 'size' => 'F20、10LPM', 'model' => 'F-2010', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S10PSU00B', 'size' => 'F20、10LPM', 'model' => 'F-2010', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 14, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S10PVC00B', 'size' => 'F20、10LPM', 'model' => 'F-2010', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 137, 'highest_stock' => 80, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S10PVC00B', 'size' => 'F20、10LPM', 'model' => 'F-2010', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 80, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S10PVC00B', 'size' => 'F20、10LPM', 'model' => 'F-2010', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 80, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PC00B', 'size' => 'F20、20LPM', 'model' => 'F-2020', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 68, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PVC00B', 'size' => 'F20、20LPM', 'model' => 'F-2020', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 42, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PSU00B', 'size' => 'F20、20LPM', 'model' => 'F-2020', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2, 'highest_stock' => 100, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PPSU20B', 'size' => 'F20、20LPM', 'model' => 'F-2020', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 71, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PC00B', 'size' => 'F20、20LPM', 'model' => 'F-2020', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 19, 'highest_stock' => 400, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PP00B', 'size' => 'F20、20LPM', 'model' => 'F-2020', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S30PC00B', 'size' => 'F20、30LPM', 'model' => 'F-2030', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 116, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S30PP00B', 'size' => 'F20、30LPM', 'model' => 'F-2030', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S30PSU20B', 'size' => 'F20、30LPM', 'model' => 'F-2030', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 28, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S30PPSU00B（A20)', 'size' => 'F20、30LPM', 'model' => 'F-2030', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 50, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S30PC00B', 'size' => 'F20、30LPM', 'model' => 'F-2030', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 10, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S30PVC00B', 'size' => 'F20、30LPM', 'model' => 'F-2030', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 8, 'highest_stock' => 70, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S30PVC00B', 'size' => 'F20、30LPM', 'model' => 'F-2030', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 70, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S40PC00B', 'size' => 'F20、40LPM', 'model' => 'F-2040', 'weight' =>48.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 26, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S40PC00B', 'size' => 'F20、40LPM', 'model' => 'F-2040', 'weight' =>48.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S40PC00B', 'size' => 'F20/40LPM', 'model' => 'F-20/40', 'weight' =>48.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 376, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S40PSU00B', 'size' => 'F20、40LPM', 'model' => 'F-2040', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 16, 'highest_stock' => 70, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S40PVC00B', 'size' => 'F20、40LPM', 'model' => 'F-2040', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 70, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S500PSU00B', 'size' => 'F20、500LPM', 'model' => 'F-20500', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 31, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S50PC00B', 'size' => 'F20、50LPM', 'model' => 'F-2050', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 163, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S50PC00B', 'size' => 'F20、50LPM', 'model' => 'F-2050', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S50PC00B', 'size' => 'F20AIR、50LPM', 'model' => 'F-2050', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 41, 'highest_stock' => 50, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S50PSU00B', 'size' => 'F20AIR、50LPM', 'model' => 'F-2050', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 50, 'highest_stock' => 70, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S50PPSU00B', 'size' => 'F20、50LPM', 'model' => 'F-2050', 'weight' =>56.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S50PVC00B', 'size' => 'F20、50LPM', 'model' => 'F-2050', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 61, 'highest_stock' => 150, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S100PSU00B', 'size' => 'F20、100LPM', 'model' => 'F-20100', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 50, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S1000PSU00B', 'size' => 'F20、1000LPM', 'model' => 'F-201000', 'weight' =>53.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 130, 'highest_stock' => 100, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF30SG', 'name' => 'F30视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S1000PSU20B', 'size' => 'F30、1000LPM', 'model' => 'F-30100', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 48, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S100PC00B', 'size' => 'F30、100LPM', 'model' => 'F-30100', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 110, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S100PSU20B', 'size' => 'F30、100LPM', 'model' => 'F-30100', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 20, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S100PVC00B', 'size' => 'F30、100LPM', 'model' => 'F-30100', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S20PC00B', 'size' => 'F30、20LPM', 'model' => 'F-3020', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 52, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S20PC00B', 'size' => 'F30、20LPM', 'model' => 'F-3020', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 20, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S20PP00B', 'size' => 'F30、20LPM', 'model' => 'F-3020', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S20PSU00B', 'size' => 'F30、20LPM', 'model' => 'F-3020', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S20PVC20B', 'size' => 'F30、20LPM', 'model' => 'F-3020', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 20, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S30PC00B', 'size' => 'F30、30LPM', 'model' => 'F-3030', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 16, 'highest_stock' => 150, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S30PC00B', 'size' => 'F30、30LPM', 'model' => 'F-3030', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 150, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S30PPSU00B', 'size' => 'F30、30LPM', 'model' => 'F-3030', 'weight' =>120, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 17, 'highest_stock' => 200, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S30PVC00B', 'size' => 'F30、30LPM', 'model' => 'F-3030', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 101, 'highest_stock' => 60, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S400PSU20B', 'size' => 'F30、400LPM', 'model' => 'F-30400', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 35, 'highest_stock' => 100, 'lowest_stock' => 5]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S400PPSU25B', 'size' => 'F30、400LPM', 'model' => 'F-30400', 'weight' =>120, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 5]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S400PVC00B', 'size' => 'F30、400LPM', 'model' => 'F-30100', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S40PC00B', 'size' => 'F30、40LPM', 'model' => 'F-3040', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S40PSU00B', 'size' => 'F30、40LPM', 'model' => 'F-3040', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 300, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S40PSU00B', 'size' => 'F30、40LPM', 'model' => 'F-3040', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S40PVC00B', 'size' => 'F30、40LPM', 'model' => 'F-3040', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 43, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S40PVC00B', 'size' => 'F30、40LPM', 'model' => 'F-3040', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S500PSU00B', 'size' => 'F30、500LPM', 'model' => 'F-30500', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 26, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S50PC00B', 'size' => 'F30、50LPM', 'model' => 'F-3050', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 40, 'highest_stock' => 0, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S50PP00B', 'size' => 'F30、50LPM', 'model' => 'F-3050', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 150, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S50PPSU00B', 'size' => 'F30、50LPM', 'model' => 'F-3050', 'weight' =>120, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 53, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S50PVC00B', 'size' => 'F30、50LPM', 'model' => 'F-3050', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 60, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S60PC00B', 'size' => 'F30、60LPM', 'model' => 'F-3060', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 29, 'highest_stock' => 160, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S60PSU00B', 'size' => 'F30、60LPM', 'model' => 'F-3060', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S60PPSU20B', 'size' => 'F30、60LPM', 'model' => 'F-3060', 'weight' =>120, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 33, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S60PVC00B', 'size' => 'F30、60LPM', 'model' => 'F-3060', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 30, 'highest_stock' => 60, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S70PC00B', 'size' => 'F30、70LPM', 'model' => 'F-3070', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 14, 'highest_stock' => 160, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S70PPSU20B(深黄）', 'size' => 'F30、70LPM', 'model' => 'F-3070', 'weight' =>120, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 23, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S70PSU20B', 'size' => 'F30、70LPM', 'model' => 'F-3070', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 50, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S70PVC00B', 'size' => 'F30、70LPM', 'model' => 'F-3070', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 100, 'highest_stock' => 60, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S70PVC00B', 'size' => 'F30、70LPM', 'model' => 'F-3070', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 60, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S80PC00B', 'size' => 'F30、80LPM', 'model' => 'F-3080', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 172, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S80PSU20B', 'size' => 'F30、80LPM', 'model' => 'F-3080', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 22, 'highest_stock' => 30, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S80PVC00B', 'size' => 'F30、80LPM', 'model' => 'F-3080', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 5, 'highest_stock' => 60, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S90PC00B', 'size' => 'F30、90LPM', 'model' => 'F-3090', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 26, 'highest_stock' => 150, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S90PC00B', 'size' => 'F30、90LPM', 'model' => 'F-3090', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 150, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S90PPSU20B', 'size' => 'F30、90LPM', 'model' => 'F-3090', 'weight' =>120, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 5, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30S90PVC00B', 'size' => 'F30、90LPM', 'model' => 'F-3090', 'weight' =>114, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LLF31SG', 'name' => 'F31视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S1000PC00B', 'size' => 'F31、1000LPM', 'model' => 'F-311000', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S100PC00B', 'size' => 'F31、100LPM', 'model' => 'F-31100', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S100PSU00B', 'size' => 'F31、100LPM', 'model' => 'F-31100', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S100PVC00B', 'size' => 'F31、100LPM', 'model' => 'F-31100', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S20PC00B', 'size' => 'F31、20LPM', 'model' => 'F-3120', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 8, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S20PVC00B', 'size' => 'F31、20LPM', 'model' => 'F-3120', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S30PC00B', 'size' => 'F31、30LPM', 'model' => 'F-3130', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 4, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S40PC00B', 'size' => 'F31、40LPM', 'model' => 'F-3140', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S50PC00B', 'size' => 'F31、50LPM', 'model' => 'F-3150', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 15, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S60PC00B', 'size' => 'F31、60LPM', 'model' => 'F-3160', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 72, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S65PC00B', 'size' => 'F31、65LPM', 'model' => 'F-3165', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S70PC00B', 'size' => 'F31、70LPM', 'model' => 'F-3150', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 27, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S80PC00B', 'size' => 'F31、80LPM', 'model' => 'F-3180', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 21, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S80PC00B', 'size' => 'F31、80LPM', 'model' => 'F-3180', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 5]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S80PVC00B', 'size' => 'F31、80LPM', 'model' => 'F-3180', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 18, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF31S90PC00B', 'size' => 'F31、90LPM', 'model' => 'F-3190', 'weight' =>66.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 29, 'highest_stock' => 100, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF32SG', 'name' => 'F32视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S110AS00B', 'size' => 'F32、110LPM', 'model' => 'F-32110', 'weight' =>167.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S110ABS00B', 'size' => 'F32、110LPM', 'model' => 'F-32110', 'weight' =>167.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 77, 'highest_stock' => 200, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S110PP00B', 'size' => 'F32、110LPM', 'model' => 'F-32', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2, 'highest_stock' => 50, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S110PVC00B', 'size' => 'F32、110LPM', 'model' => 'F-32', 'weight' =>214.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 53, 'highest_stock' => 200, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S15ABS30B', 'size' => 'F32、15LPM', 'model' => 'F3215', 'weight' =>167.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 45, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S15AS30B', 'size' => 'F32、15LPM', 'model' => 'F3215', 'weight' =>167.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S15PVC00B', 'size' => 'F32、15LPM', 'model' => 'F3215', 'weight' =>214.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S15PVC30B', 'size' => 'F32、15LPM', 'model' => 'F3215', 'weight' =>214.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 21, 'highest_stock' => 200, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S25ABS30B', 'size' => 'F32、25LPM', 'model' => 'F3225', 'weight' =>167.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 21, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S65AS30B', 'size' => 'F32、65LPM', 'model' => 'F3265', 'weight' =>167.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 163, 'highest_stock' => 200, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S65PP00B', 'size' => 'F32、65LPM', 'model' => 'F3265', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32S65PVC30B', 'size' => 'F32、65LPM', 'model' => 'F3265', 'weight' =>214.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 27, 'highest_stock' => 200, 'lowest_stock' => 20]);

        $product = Product::create(['code' => 'LLF45SG', 'name' => 'F45视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S100PC00B', 'size' => 'F45/60、100LPM', 'model' => 'F-45/60100', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 9, 'highest_stock' => 50, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S100PSU20B', 'size' => 'F45/60、100LPM', 'model' => 'F-45/60100', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 35, 'highest_stock' => 100, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S150PC00B', 'size' => 'F45/60、150LPM', 'model' => 'F-45/60150', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 27, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S150PSU00B', 'size' => 'F45/60、150LPM', 'model' => 'F-45/60150', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 16, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S200PC00B', 'size' => 'F45/60、200LPM', 'model' => 'F-45/60200', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S200PSU25B', 'size' => 'F45/60、200LPM', 'model' => 'F-45/60200', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 45, 'highest_stock' => 0, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S250PC00B', 'size' => 'F45/60、250LPM', 'model' => 'F-45/60250', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 36, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S250PSU20B', 'size' => 'F45/60、250LPM', 'model' => 'F-45/60250', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 5, 'highest_stock' => 150, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S250PSU20B', 'size' => 'F45/60、250LPM', 'model' => 'F-45/60250', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 21, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S300PC00B', 'size' => 'F45/60、300LPM', 'model' => 'F-45/60300', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 64, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S300PC00B', 'size' => 'F45/60、300LPM', 'model' => 'F-45/60300', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S300PSU20B', 'size' => 'F45/60、300LPM', 'model' => 'F-45/60300', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 5, 'highest_stock' => 50, 'lowest_stock' => 5]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S300PVC00B', 'size' => 'F45/60、300LPM', 'model' => 'F-45/60300', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7, 'highest_stock' => 50, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S300PVC00B', 'size' => 'F45/60、300LPM', 'model' => 'F-45/60300', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 313, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S450PC00B', 'size' => 'F45/60、450LPM', 'model' => 'F-45/60450', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 18, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S450PC00B', 'size' => 'F45/60、450LPM', 'model' => 'F-45/60450', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 22, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S450PSU00B', 'size' => 'F45/60、450LPM', 'model' => 'F-45/60450', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 128, 'highest_stock' => 100, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S600PC00B', 'size' => 'F45/60、600LPM', 'model' => 'F-45/60600', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 39, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S600PC00B', 'size' => 'F45/60、600LPM', 'model' => 'F-45/60600', 'weight' =>353, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 50, 'lowest_stock' => 20]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S600PSU0B', 'size' => 'F45/60、600LPM', 'model' => 'F-45/60600', 'weight' =>369, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 10, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S600PVC00B', 'size' => 'F45/60、600LPM', 'model' => 'F-45/60600', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 25, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45S600PVC00B', 'size' => 'F45、600LPM', 'model' => 'F-45600', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 32, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LLF50SG', 'name' => 'F50视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF50S150PC00B(J50)', 'size' => 'F50、150LPM', 'model' => 'J-50150', 'weight' =>384.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 22, 'highest_stock' => 30, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF50S250PC00B(J50)', 'size' => 'F50、250LPM', 'model' => 'J-50250', 'weight' =>384.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 10, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF50S450PC00B(J50)', 'size' => 'F50、450LPM', 'model' => 'J-50450', 'weight' =>384.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 30, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF50S450PC00Y(J50)', 'size' => 'F50、450LPM', 'model' => 'J-50450', 'weight' =>384.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 15, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF50S600PC00B(J50)', 'size' => 'F50、600LPM', 'model' => 'J-50600', 'weight' =>384.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 29, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF50S600PC00B(J50)', 'size' => 'F50、600LPM', 'model' => 'J-50600', 'weight' =>384.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LLJ15SG', 'name' => 'J15视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15S4PC00B', 'size' => 'J15、4.0LPM', 'model' => 'J-1504', 'weight' =>52.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15S5PC00B', 'size' => 'J15、5.0LPM', 'model' => 'J-1505', 'weight' =>52.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 26, 'highest_stock' => 500, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15S8PC00B', 'size' => 'J15、8.0LPM', 'model' => 'J-1508', 'weight' =>52.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 66, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15S10PC00B', 'size' => 'J15、10LPM', 'model' => 'J-1510', 'weight' =>52.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 11, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15S15PC00B', 'size' => 'J15、15LPM', 'model' => 'J-1515', 'weight' =>52.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 13, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15S20PC00B', 'size' => 'J15、20LPM', 'model' => 'J-1520', 'weight' =>52.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 6, 'highest_stock' => 200, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLJ20SG', 'name' => 'J20视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20S10PC00B', 'size' => 'J20、10LPM', 'model' => 'J-2010', 'weight' =>88.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 36, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20S20PC00B', 'size' => 'J20、20LPM', 'model' => 'J-2020', 'weight' =>88.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 98, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20S30PC00B', 'size' => 'J20、30LPM', 'model' => 'J-2030', 'weight' =>88.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 53, 'highest_stock' => 500, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20S30PC00B', 'size' => 'J20、30LPM', 'model' => 'J-2030', 'weight' =>89.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 121, 'highest_stock' => 500, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20S30PVC00B', 'size' => 'J20、30LPM', 'model' => 'J-2030', 'weight' =>102, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 5, 'highest_stock' => 100, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20S40PC00B', 'size' => 'J20、40LPM', 'model' => 'J-2040', 'weight' =>89.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 33, 'highest_stock' => 200, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20S50PC00B', 'size' => 'J20、50LPM', 'model' => 'J-2050', 'weight' =>89.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 24, 'highest_stock' => 200, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLJ25SG', 'name' => 'J25视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S1000PC00B', 'size' => 'J25、1000LPM', 'model' => 'J-251000', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S100PC00B', 'size' => 'J25、100LPM', 'model' => 'J-25100', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 42, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S10PC00B', 'size' => 'J25、10LPM', 'model' => 'J-2510', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S30PC00B', 'size' => 'J25、30LPM', 'model' => 'J-2530', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 29, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S40PC00B', 'size' => 'J25、40LPM', 'model' => 'J-2540', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 4, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S50PC00B', 'size' => 'J25、50LPM', 'model' => 'J-2550', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S60PC00B', 'size' => 'J25、60LPM', 'model' => 'J-2560', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 67, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25S80PC00B', 'size' => 'J25、80LPM', 'model' => 'J-2580', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 39, 'highest_stock' => 100, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLA20SG', 'name' => 'F20空气视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S1000PC00B', 'size' => 'F20、1000NLPM', 'model' => 'F20AIR-1000', 'weight' =>115.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S1000PSU00B', 'size' => 'F20、1000NLPM', 'model' => 'F20AIR-1000', 'weight' =>56.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 220, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S100PC00B', 'size' => 'F20、100NLPM', 'model' => 'F20AIR-100', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S100PSU00B', 'size' => 'F20、100NLPM', 'model' => 'F20AIR-100', 'weight' =>56.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S30PC00B', 'size' => 'F20、30NLPM', 'model' => 'F20AIR-30', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S30PSU00B', 'size' => 'F20、30NLPM', 'model' => 'F20AIR-30', 'weight' =>56.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S500PC00B', 'size' => 'F20、500NLPM', 'model' => 'F20AIR-500', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S500PSU00B', 'size' => 'F20、500NLPM', 'model' => 'F20AIR-500', 'weight' =>56.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S500PVC00B', 'size' => 'F20、500NLPM', 'model' => 'F20AIR-500', 'weight' =>55.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S50PC00B', 'size' => 'F20、50NLPM', 'model' => 'F20AIR-50', 'weight' =>48, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA20S50PSU00B', 'size' => 'F20、50NLPM', 'model' => 'F20AIR-50', 'weight' =>56.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLA30SG', 'name' => 'F30空气视管', 'category_id' => 8,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30S1000PC00B', 'size' => 'F30、1000NLPM', 'model' => 'F30AIR-1000', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30S1000PSU00B', 'size' => 'F30、1000NLPM', 'model' => 'F30AIR-1000', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30S400PSU00B', 'size' => 'F30、400NLPM', 'model' => 'F30AIR-400', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30S500PC00B', 'size' => 'F30、500NLPM', 'model' => 'F30AIR-500', 'weight' =>103, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLA30S500PSU00B', 'size' => 'F30、500NLPM', 'model' => 'F30AIR-500', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF10LM', 'name' => 'F10螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10SPVC85B', 'size' => 'F10、10LPM', 'model' => 'F-1010', 'weight' =>6.97, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2236, 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLF20LM', 'name' => 'F20螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF20PVC85B', 'size' => 'F20', 'model' => 'F-20', 'weight' =>13.32, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1660, 'highest_stock' => 3000, 'lowest_stock' => 300]);

        $product = Product::create(['code' => 'LLF30LM', 'name' => 'F30螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30L30PVC85B', 'size' => '1寸Φ32', 'model' => '17.3', 'weight' =>17.27, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 310, 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLF32LM', 'name' => 'F32螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32PVC85B', 'size' => 'F32', 'model' => 'F-32', 'weight' =>17.27, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1268, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32PVC85B（细牙）', 'size' => 'F32', 'model' => 'F-32', 'weight' =>53.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 119, 'highest_stock' => 3000, 'lowest_stock' => 100]);

        $product = Product::create(['code' => 'LLF45LM', 'name' => 'F45螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PVC85B', 'size' => 'F45', 'model' => '', 'weight' =>88.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 819, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ15LM', 'name' => 'J15螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ15PVC85B', 'size' => 'J15', 'model' => 'J-1505', 'weight' =>20.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 385, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ20LM', 'name' => 'J20螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ20PVC85B', 'size' => 'J20', 'model' => '', 'weight' =>37.1, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 566, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ25LM', 'name' => 'J25螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ25PVC85B', 'size' => 'J25', 'model' => 'J-25', 'weight' =>38.06, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1182, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ50LM', 'name' => 'J50螺母', 'category_id' => 12,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ50PVC85B', 'size' => 'J50', 'model' => '', 'weight' =>53.63, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 16, 'highest_stock' => 1000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLF10JQ', 'name' => 'F10胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10VITON45B', 'size' => 'F10、', 'model' => 'Φ29*2', 'weight' =>0.53, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1241, 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10SEPDM02B', 'size' => 'F10', 'model' => 'Φ29*2', 'weight' =>0.32, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2448, 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLF20JQ', 'name' => 'F20胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20SEPDM02B', 'size' => 'F20', 'model' => 'Φ33*2', 'weight' =>0.34, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2704, 'highest_stock' => 10000, 'lowest_stock' => 500]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20VITON45B', 'size' => 'F20', 'model' => 'Φ33*2', 'weight' =>0.54, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1959, 'highest_stock' => 10000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLF30JQ', 'name' => 'F30胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30JVITON45B', 'size' => 'F30-Φ42*2（原登记38*3）', 'model' => 'Φ42*2', 'weight' =>0.85, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1280, 'highest_stock' => 5000, 'lowest_stock' => 500]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30JEPDM02B', 'size' => 'J25-Φ42*2（原登记38*3）', 'model' => 'Φ42*2', 'weight' =>0.45, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3162, 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLF45JQ', 'name' => 'F45胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45FO45B', 'size' => 'F45系列导轨用（￠14*4）', 'model' => '￠14*4', 'weight' =>0.78, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2002, 'highest_stock' => 5000, 'lowest_stock' => 300]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF45FO45B', 'size' => 'F45系列（42*4.3？）', 'model' => 'F-45', 'weight' =>4.7, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LLF60JQ', 'name' => 'F60胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF60S600PSU00B(F45)', 'size' => 'F60、600LPM（¢72*5MM ）', 'model' => 'F-60600', 'weight' =>7.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2070, 'highest_stock' => 5000, 'lowest_stock' => 300]);

        $product = Product::create(['code' => 'LLJ15JQ', 'name' => 'J15胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15SEPDM02B', 'size' => 'J15', 'model' => 'Φ24*3', 'weight' =>0.52, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1896, 'highest_stock' => 5000, 'lowest_stock' => 300]);

        $product = Product::create(['code' => 'LLJ20JQ', 'name' => 'J20胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20EPDM02B', 'size' => 'J20', 'model' => 'Φ34*3', 'weight' =>0.83, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2261, 'highest_stock' => 5000, 'lowest_stock' => 300]);

        $product = Product::create(['code' => 'LLJ25JQ', 'name' => 'J25胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25EPDM02B', 'size' => 'F-45', 'model' => '￠42*4H', 'weight' =>1.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 796, 'highest_stock' => 5000, 'lowest_stock' => 300]);

        $product = Product::create(['code' => 'LLJ50JQ', 'name' => 'J50胶圈', 'category_id' => 14,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ50EPDM02B（共F45胶圈）', 'size' => 'F60、600LPM（¢72*5MM ）', 'model' => 'J-50', 'weight' =>6.26, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 2000, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LLF10GZ', 'name' => 'F10盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10UPVC02B', 'size' => 'F10', 'model' => 'F-10', 'weight' =>0.89, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1652, 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLJ15GZ', 'name' => 'J15盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15PP00B', 'size' => 'J15', 'model' => 'J-15', 'weight' =>0.73, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2348, 'highest_stock' => 2000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15PVDF00B', 'size' => 'J15', 'model' => 'J-15', 'weight' =>0.89, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 332, 'highest_stock' => 500, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LLF20GZ', 'name' => 'F20盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20PVC85B', 'size' => 'F20', 'model' => 'F-20', 'weight' =>1.49, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3158, 'highest_stock' => 10000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLJ20GZ', 'name' => 'J20盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ20PP00B', 'size' => 'J20', 'model' => 'J-20', 'weight' =>1.62, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => -8, 'highest_stock' => 5000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLF30GZ', 'name' => 'F30盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30G20PVC00B', 'size' => 'F30', 'model' => 'F-1020', 'weight' =>2.59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 6929, 'highest_stock' => 10000, 'lowest_stock' => 1000]);

        $product = Product::create(['code' => 'LLJ25GZ', 'name' => 'J25盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ25PP00B', 'size' => 'J25', 'model' => 'F-1020', 'weight' =>2.66, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1040, 'highest_stock' => 5000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LLF45GZ', 'name' => 'F45盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PP00B（同J50)', 'size' => 'J50(F45)', 'model' => '', 'weight' =>11.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2030, 'highest_stock' => 5000, 'lowest_stock' => 300]);

        $product = Product::create(['code' => 'LLF32GZ', 'name' => 'F32盖子', 'category_id' => 15,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF32ABS45B', 'size' => 'F32', 'model' => '', 'weight' =>10.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1926, 'highest_stock' => 2000, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF32ABS45B', 'size' => 'F32', 'model' => '', 'weight' =>7.7, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1930, 'highest_stock' => 2000, 'lowest_stock' => 50]);

        $product = Product::create(['code' => 'LLF10JT', 'name' => 'F10接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PVC85B-22', 'size' => 'F10', 'model' => 'F-1010', 'weight' =>11.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2964, 'highest_stock' => 3000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PVC85B-20', 'size' => 'F10', 'model' => 'F-1010', 'weight' =>13.91, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 480, 'highest_stock' => 2000, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PVC85B-25', 'size' => 'F10', 'model' => 'F-1010', 'weight' =>10.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 316, 'highest_stock' => 500, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF10PVC85B', 'size' => 'F10', 'model' => '', 'weight' =>11.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 98, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PVC00B-core', 'size' => 'F10、10LPM', 'model' => 'F-1010', 'weight' =>11.62, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 471, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S10PVC00B', 'size' => 'F10、10LPM', 'model' => 'F-1010', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PVC02B-et', 'size' => 'F10', 'model' => 'F-1020', 'weight' =>10.39, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 232, 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PVC02B-it', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>19.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 163, 'highest_stock' => 0, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PVDF00B-et', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>15.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 157, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PVDF00B-it', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>27.9, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LLF20JT', 'name' => 'F20接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF20SPVC85B', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>17.54, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 780, 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20PVC85B', 'size' => 'F20', 'model' => 'F20', 'weight' =>19.14, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 353, 'highest_stock' => 500, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20PVC85B-et', 'size' => 'F20', 'model' => 'F-20', 'weight' =>23.54, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 173, 'highest_stock' => 500, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PP80B-et', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>8.25, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 200, 'lowest_stock' => 1]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PP80B-it', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>9.25, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 300, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PVC00B', 'size' => 'F20、20LPM', 'model' => 'F-1020', 'weight' =>16.8, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 30]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PVC85B', 'size' => 'F20', 'model' => 'F-20', 'weight' =>22.43, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 576, 'highest_stock' => 1000, 'lowest_stock' => 100]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF20S20PVC85B', 'size' => 'F20', 'model' => 'F-1020', 'weight' =>16.77, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1167, 'highest_stock' => 3000, 'lowest_stock' => 300]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF20PVC85B', 'size' => 'F20', 'model' => '', 'weight' =>15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 200, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF10S20PP80B(F20)', 'size' => 'F10、20LPM', 'model' => 'F-1020', 'weight' =>13.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 230, 'highest_stock' => 200, 'lowest_stock' => 10]);

        $product = Product::create(['code' => 'LLF30JT', 'name' => 'F30接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30C30PVC85B', 'size' => '1寸', 'model' => '34.2', 'weight' =>34.2, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 952, 'highest_stock' => 1500, 'lowest_stock' => 300]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30CPP80B', 'size' => '1寸', 'model' => '', 'weight' =>31, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 224, 'highest_stock' => 200, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30C30PVC00B', 'size' => '1寸', 'model' => '', 'weight' =>37, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 344, 'highest_stock' => 200, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF30W30PVC02B', 'size' => '1寸', 'model' => '', 'weight' =>31.02, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2219, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF30NPVC85B', 'size' => 'F30', 'model' => '', 'weight' =>39.59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 14, 'highest_stock' => 500, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF30NPP00B', 'size' => 'F30', 'model' => '', 'weight' =>34.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 8, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF30NPVDF00B', 'size' => 'F30', 'model' => '', 'weight' =>59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF30WPVC85B', 'size' => 'F30', 'model' => '', 'weight' =>31.02, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2140, 'highest_stock' => 500, 'lowest_stock' => 30]);

        $product = Product::create(['code' => 'LLF320JT', 'name' => 'F320接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32PVC85B', 'size' => 'F32', 'model' => 'F-32', 'weight' =>53.63, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 475, 'highest_stock' => 500, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLF32PVC85B(细牙）', 'size' => 'F32', 'model' => 'F-32', 'weight' =>37.37, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 19, 'highest_stock' => 3000, 'lowest_stock' => 100]);

        $product = Product::create(['code' => 'LLF60JT', 'name' => 'F60接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF60PVC85B(F45)', 'size' => 'F45', 'model' => '', 'weight' =>113, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 411, 'highest_stock' => 2000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF60PVC85B(F45)', 'size' => 'F60', 'model' => '', 'weight' =>144.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 110, 'highest_stock' => 200, 'lowest_stock' => 10]);

        $product = Product::create(['code' => 'LLF45JT', 'name' => 'F45接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PVC85B', 'size' => 'F45', 'model' => '', 'weight' =>107, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 538, 'highest_stock' => 2000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PVC85B', 'size' => 'F45', 'model' => '', 'weight' =>88.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 23, 'highest_stock' => 200, 'lowest_stock' => 50]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PP80B', 'size' => 'F45', 'model' => '', 'weight' =>100, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 330, 'highest_stock' => 200, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PP80B(加工件)', 'size' => 'F45', 'model' => '', 'weight' =>97, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 100, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PP80B(加工件)', 'size' => 'F45', 'model' => '', 'weight' =>127, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 26, 'highest_stock' => 100, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PVC85B(加工件)', 'size' => 'F45', 'model' => 'F45', 'weight' =>118.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 12, 'highest_stock' => 100, 'lowest_stock' => 10]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PVC85B', 'size' => 'F45', 'model' => '', 'weight' =>129, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 20, 'highest_stock' => 50, 'lowest_stock' => 5]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFF45PVC85B（F60)', 'size' => 'F45', 'model' => '', 'weight' =>150, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 30, 'highest_stock' => 50, 'lowest_stock' => 5]);

        $product = Product::create(['code' => 'LLJ15JT', 'name' => 'J15接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ15PVC85B', 'size' => 'J15', 'model' => '', 'weight' =>12.6, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1386, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ20JT', 'name' => 'J20接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ20PVC85B', 'size' => 'J20', 'model' => '', 'weight' =>20.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 543, 'highest_stock' => 2000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ20PVC85B', 'size' => 'J20', 'model' => '', 'weight' =>20.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 144, 'highest_stock' => 300, 'lowest_stock' => 10]);

        $product = Product::create(['code' => 'LLJ15JT', 'name' => 'J15接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ25PVC85B', 'size' => 'J25', 'model' => '', 'weight' =>53.63, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 666, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LLJ50JT', 'name' => 'J50接头', 'category_id' => 11,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLFJ50PVC85B', 'size' => 'J50', 'model' => '', 'weight' =>122, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 2000, 'lowest_stock' => 200]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LLJ15S10PVC00B（原J10)', 'size' => 'J15', 'model' => 'J-15', 'weight' =>14.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1300, 'highest_stock' => 500, 'lowest_stock' => 50]);


        // 轮片
        $product = Product::create(['code' => 'LP0101', 'name' => 'Φ25PVC滚轮片101', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0101APVC30B', 'size' => 'Φ25*Φ9*13L', 'model' => 'HS-2510C', 'weight' =>4.38, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 405, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LP0102', 'name' => 'Φ30PVC滚轮片102', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0102APVC15B', 'size' => 'Φ30*Φ9*12L', 'model' => 'HS-3010C', 'weight' =>3.445, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0102APVC30B', 'size' => 'Φ30*Φ9*12L', 'model' => '', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0102APVC35B', 'size' => 'Φ30*Φ9*12L', 'model' => 'HS-3010C', 'weight' =>3.265, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1286, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LP0103', 'name' => 'Φ35PVC滚轮片103', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0103APVC30B', 'size' => 'Φ35*Φ9*33L', 'model' => 'HS-3510C', 'weight' =>7.405, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 35, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LP0104', 'name' => 'Φ40PVC滚轮片104', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0104APVC30B', 'size' => 'Φ40*Φ7*11L', 'model' => 'HS-3510C', 'weight' =>6.91, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2514, 'highest_stock' => 3000, 'lowest_stock' => 500]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0104BPVC30B', 'size' => 'Φ40*Φ9*11L', 'model' => 'HS-3510C', 'weight' =>6.91, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 172, 'highest_stock' => 3000, 'lowest_stock' => 500]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0104CPVC00B(30B)', 'size' => 'Φ40*Φ11*11L', 'model' => 'HS-3510C', 'weight' =>6.04, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 43, 'highest_stock' => 3000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LP0105', 'name' => 'Φ40PVC滚轮片105', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0105APVC30B', 'size' => 'Φ40*Φ7*11L', 'model' => 'HS-4008C', 'weight' =>4.63, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0105BPVC30B', 'size' => 'Φ40*Φ9.5*11L', 'model' => 'HS-4008C', 'weight' =>4.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2260, 'highest_stock' => 3000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LP0106', 'name' => 'Φ43PVC滚轮片106', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0106APVC35B', 'size' => 'Φ43*Φ9*28L', 'model' => 'HS-4310C', 'weight' =>9.2, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1758, 'highest_stock' => 3000, 'lowest_stock' => 500]);

        $product = Product::create(['code' => 'LP0107', 'name' => 'Φ45PVC滚轮片107', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0107APVC30B', 'size' => 'Φ45*Φ9*33L', 'model' => 'HS-4510C', 'weight' =>8.53, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 927, 'highest_stock' => 3000, 'lowest_stock' => 500]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0107APVC35B', 'size' => 'Φ45*Φ9*33L', 'model' => 'HS-4510C', 'weight' =>8.53, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LP0108', 'name' => 'Φ50PVC滚轮片108', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0108APVC00B', 'size' => 'Φ50*Φ6*9.5L', 'model' => 'HS-5006C', 'weight' =>10.1, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0108APVC20B', 'size' => 'Φ50*Φ6*9.6L', 'model' => 'HS-5006C', 'weight' =>10.1, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0108APVC35B', 'size' => 'Φ50*Φ6*9.5L', 'model' => 'HS-5007C', 'weight' =>10.1, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1950, 'highest_stock' => 2000, 'lowest_stock' => 200]);

        $product = Product::create(['code' => 'LP0109', 'name' => 'Φ50PVC滚轮片109', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0109APVC30B', 'size' => 'Φ50*Φ8*26L', 'model' => 'HS-5008C', 'weight' =>11.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 520, 'highest_stock' => 2000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0109APVC30B（有效长短0.4MM)', 'size' => 'Φ50*Φ8*32L', 'model' => 'HS-5008C', 'weight' =>11.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 300, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0109APVC35B', 'size' => 'Φ50*Φ8*26L', 'model' => 'HS-5008C', 'weight' =>11.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LP0110', 'name' => 'Φ50PVC滚轮片110', 'category_id' => 18,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0110APVC00B', 'size' => 'Φ50*Φ9*32L', 'model' => 'HS-5010C', 'weight' =>11.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 2000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0110APVC30B', 'size' => 'Φ50*Φ9*32L', 'model' => 'HS-5010C', 'weight' =>11.3, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 43, 'highest_stock' => 2000, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LP0141', 'name' => 'Φ22包胶滚轮片141', 'category_id' => 20,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0141APP+TPV25B', 'size' => '￠22*￠8*22.5L*6W', 'model' => 'HS-872', 'weight' =>2.15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0141ATPV20B', 'size' => 'Φ22*Φ9.5*22.5L', 'model' => 'HS-206', 'weight' =>2.15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 950, 'highest_stock' => 1000, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0141ATPV29B', 'size' => 'Φ22*Φ9.5*22.5L', 'model' => 'HS-206', 'weight' =>2.15, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 590, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0141BPP+TPV25B', 'size' => '￠22*￠10*22.5L*6W', 'model' => 'HS-872', 'weight' =>2.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1448, 'highest_stock' => 1000, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LP0142', 'name' => 'Φ22包胶滚轮片142', 'category_id' => 20,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0142APP+TPV20B', 'size' => 'Φ22*Φ9.5*15L', 'model' => 'HS-205', 'weight' =>1.322, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 425, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0142APP+TPV25B', 'size' => 'Φ22*Φ9.5*15L', 'model' => 'HS-205', 'weight' =>1.322, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2500, 'highest_stock' => 2000, 'lowest_stock' => 500]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0142BPP00B', 'size' => 'Φ22*Φ10*15L', 'model' => 'HS-205', 'weight' =>1.22, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 4723, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0142BPP+PVC00B', 'size' => 'Φ22*Φ10*15L', 'model' => 'HS-205', 'weight' =>1.3065, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 6800, 'highest_stock' => 3000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0142BPP+TPV25B', 'size' => 'Φ22*Φ10*15L', 'model' => 'HS-205', 'weight' =>1.3695, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 197, 'highest_stock' => 10000, 'lowest_stock' => 1000]);

        $product = Product::create(['code' => 'LP0143', 'name' => 'Φ30包胶滚轮片143', 'category_id' => 20,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0143APP+TPV85B', 'size' => 'Φ30*Φ10*20L', 'model' => 'HS-620', 'weight' =>2.5714, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0143BPP+TPV02B', 'size' => 'Φ30*Φ10*20L', 'model' => 'HS-620', 'weight' =>2.81, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0143BPP+TPV25B', 'size' => 'Φ30*Φ10*20L', 'model' => 'HS-620', 'weight' =>2.81, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 7787, 'highest_stock' => 10000, 'lowest_stock' => 2000]);

        $product = Product::create(['code' => 'LP0144', 'name' => 'Φ40包胶滚轮片144', 'category_id' => 20,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144APP 01B', 'size' => 'Φ40*Φ8*33L', 'model' => 'HS-4008B', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144APP+PVC15B', 'size' => 'Φ40*Φ8*33L', 'model' => 'HS-4008B', 'weight' =>4.851, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 283, 'highest_stock' => 1000, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144APVC15B', 'size' => 'Φ40', 'model' => 'HS-4010B', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144BPP+PVC15B', 'size' => 'Φ40*Φ10*33L', 'model' => 'HS-4010B', 'weight' =>5.409, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 1523, 'highest_stock' => 3000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144BPP00B', 'size' => 'Φ10*33L', 'model' => 'HS-4010B', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144CPP+PVC15B(外购）', 'size' => 'Φ40*Φ12*33L', 'model' => 'HS-4012B', 'weight' =>5.37, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 2236, 'highest_stock' => 5000, 'lowest_stock' => 2000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144CPP+TPV25B(外购）', 'size' => 'Φ40*Φ12*33L', 'model' => 'HS-4012B', 'weight' =>5.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 214, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144CPP+TPV25B(外购短30丝）', 'size' => 'Φ40*Φ12*33L', 'model' => 'HS-4012B', 'weight' =>5.5, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 500, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0144CPP+PVC20B(外购）', 'size' => 'Φ40*Φ12*33L', 'model' => 'HS-4012B', 'weight' =>5.37, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LP0145', 'name' => 'Φ25包胶滚轮片145', 'category_id' => 20,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0145APP+TPV02B', 'size' => 'Φ25*8*15L', 'model' => 'HS-970', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3458, 'highest_stock' => 2000, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0145APP+TPV25B(非标)', 'size' => 'Φ25*8*20L', 'model' => 'HS-970', 'weight' =>0, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);

        $product = Product::create(['code' => 'LP0146', 'name' => 'Φ40包胶滚轮片146', 'category_id' => 20,]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0146APP+TPV25B', 'size' => 'Φ40*Φ9.5*20L', 'model' => 'HS-163', 'weight' =>3.59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3238, 'highest_stock' => 5000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0146CPP+TPV25B', 'size' => 'Φ40*Φ8*20L', 'model' => 'HS-163', 'weight' =>3.59, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0146BPP+TPV25B', 'size' => 'Φ40*Φ10*20L', 'model' => 'HS-4010B', 'weight' =>3.3925, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 11146, 'highest_stock' => 10000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0146BPP+TPV25B(耐温100度）', 'size' => 'Φ40*Φ10*20L', 'model' => 'HS-4010B', 'weight' =>3.393, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 11.82, 'highest_stock' => 10000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0146BPP+TPV02B', 'size' => 'Φ40*Φ10*20L', 'model' => 'HS-4010B', 'weight' =>3.393, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 3188, 'highest_stock' => 5000, 'lowest_stock' => 1000]);
        $product_sku = ProductSku::create(['product_id' => $product->id, 'code' => 'LP0146BPP+TPV85B', 'size' => 'Φ40*Φ10*20L', 'model' => 'HS-970', 'weight' =>3.393, 'cost_price' => 15.4,]);
        Inventory::create(['sku_id' => $product_sku->id, 'product_id' => $product->id, 'stock' => 0, 'highest_stock' => 0, 'lowest_stock' => 0]);
    }
}