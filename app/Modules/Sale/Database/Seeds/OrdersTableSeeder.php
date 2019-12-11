<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/11
 * Time: 12:04
 */

namespace App\Modules\Sale\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\OrderItem;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $order = Order::create(['code' => 'xhspo20191211001', 'customer_id' => 1]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 2,
            'sku_id' => 6,
            'title' => 'PP单面隔膜压力表7KG',
            'unit' => '个',
            'quantity' => '50',
            'price' => 65,
            'delivery_date' => '2019-12-25',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 8,
            'sku_id' => 22,
            'title' => 'kingspring流量计011 150JPM',
            'unit' => '个',
            'quantity' => '10',
            'price' => 350,
            'delivery_date' => '2019-12-25',
        ]);

        $order = Order::create(['code' => 'xhspo20191211001', 'customer_id' => 3]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 2,
            'sku_id' => 7,
            'title' => 'PP单面隔膜压力表10KG',
            'unit' => '个',
            'quantity' => '50',
            'price' => 65,
            'delivery_date' => '2019-12-25',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 8,
            'sku_id' => 23,
            'title' => 'kingspring流量计011 200JPM',
            'unit' => '个',
            'quantity' => '10',
            'price' => 350,
            'delivery_date' => '2019-12-25',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 6,
            'sku_id' => 16,
            'title' => '孟山都滚轮片162A',
            'unit' => '个',
            'quantity' => '300',
            'price' => 1.5,
            'delivery_date' => '2019-12-18',
        ]);

        $order = Order::create(['code' => 'xhspo20191211001', 'customer_id' => 2]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 2,
            'sku_id' => 6,
            'title' => 'PP单面隔膜压力表7KG',
            'unit' => '个',
            'quantity' => '50',
            'price' => 65,
            'delivery_date' => '2019-12-25',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 8,
            'sku_id' => 22,
            'title' => 'kingspring流量计011 150JPM',
            'unit' => '个',
            'quantity' => '10',
            'price' => 350,
            'delivery_date' => '2019-12-25',
        ]);
    }
}