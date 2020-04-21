<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/10
 * Time: 19:19
 */

namespace App\Modules\Purchase\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Modules\Purchase\Models\PurchaseOrder;
use App\Modules\Purchase\Models\PurchaseOrderItem;

class PurchaseOrdersTableSeeder extends Seeder
{
    public function run()
    {
        $order = PurchaseOrder::create(['code' => 'xhspo20191210001', 'supplier_id' => 1, 'payment_method' => 1, 'tax' => 1, 'currency_code' => 'CNY', 'delivery_date' => Carbon::now()->addDays(5)->toDateString(),'status' => 3, 'user_id' => 2]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id' => 7,
            'sku_id' => 19,
            'title' => 'pp滚轮片124A',
            'unit' => '个',
            'quantity' => '2000',
            'price' => 0.3,
        ]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id' => 7,
            'sku_id' => 20,
            'title' => 'pp滚轮片124B',
            'unit' => '个',
            'quantity' => '2000',
            'price' => 0.3,
        ]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id' => 7,
            'sku_id' => 21,
            'title' => 'pp滚轮片124C',
            'unit' => '个',
            'quantity' => '2000',
            'price' => 0.3,
        ]);

        $order = PurchaseOrder::create(['code' => 'xhspo20191210002', 'supplier_id' => 1, 'payment_method' => 1, 'tax' => 1, 'currency_code' => 'CNY', 'delivery_date' => Carbon::now()->addDays(3)->toDateString(),'status' => 1, 'user_id' => 3]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id' => 11,
            'sku_id' => 29,
            'title' => '包胶滚轮片144骨架A',
            'unit' => '个',
            'quantity' => '2000',
            'price' => 0.25,
        ]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id' => 11,
            'sku_id' => 30,
            'title' => '包胶滚轮片144骨架B',
            'unit' => '个',
            'quantity' => '2000',
            'price' => 0.25,
        ]);
        $order = PurchaseOrder::create(['code' => 'xhspo20191210003', 'supplier_id' => 2, 'payment_method' => 3, 'tax' => 3, 'currency_code' => 'CNY', 'delivery_date' => Carbon::now()->addDays(6)->toDateString(),'status' => 3, 'user_id' => 4]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id' => 15,
            'sku_id' => 38,
            'title' => '565喷咀头A',
            'unit' => '个',
            'quantity' => '800',
            'price' => 2.2,
        ]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $order->id,
            'product_id' => 16,
            'sku_id' => 42,
            'title' => '565喷咀底座B',
            'unit' => '个',
            'quantity' => '800',
            'price' => 1.2,
        ]);
    }
}