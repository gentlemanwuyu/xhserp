<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/11
 * Time: 12:04
 */

namespace App\Modules\Sale\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\OrderItem;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $order = Order::create(['code' => 'xhsso20191211001', 'customer_id' => 1, 'payment_method' => \PaymentMethod::MONTHLY, 'tax' => \Tax::SEVENTEEN, 'currency_code' => 'CNY', 'delivery_date' => Carbon::now()->addDays(5)->toDateString() , 'status' => Order::AGREED, 'payment_status' => Order::PENDING_PAYMENT, 'user_id' => 5]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 2,
            'sku_id' => 6,
            'title' => 'PP单面隔膜压力表7KG',
            'unit' => '个',
            'quantity' => '50',
            'price' => 65,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 8,
            'sku_id' => 22,
            'title' => 'kingspring流量计011 150JPM',
            'unit' => '个',
            'quantity' => '10',
            'price' => 350,
        ]);

        $order = Order::create(['code' => 'xhsso20191211002', 'customer_id' => 3, 'payment_method' => \PaymentMethod::MONTHLY, 'tax' => \Tax::SEVENTEEN, 'currency_code' => 'USD', 'delivery_date' => Carbon::now()->addDays(7)->toDateString() , 'status' => Order::AGREED, 'payment_status' => Order::PENDING_PAYMENT, 'user_id' => 6]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 2,
            'sku_id' => 7,
            'title' => 'PP单面隔膜压力表10KG',
            'unit' => '个',
            'quantity' => '50',
            'price' => 65,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 8,
            'sku_id' => 23,
            'title' => 'kingspring流量计011 200JPM',
            'unit' => '个',
            'quantity' => '10',
            'price' => 350,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 6,
            'sku_id' => 16,
            'title' => '孟山都滚轮片162A',
            'unit' => '个',
            'quantity' => '300',
            'price' => 1.5,
        ]);

        $order = Order::create(['code' => 'xhsso20191211003', 'customer_id' => 2, 'payment_method' => \PaymentMethod::CREDIT, 'tax' => \Tax::NONE, 'currency_code' => 'CNY', 'delivery_date' => Carbon::now()->addDays(2)->toDateString() , 'status' => Order::AGREED, 'payment_status' => Order::PENDING_PAYMENT, 'user_id' => 7]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 2,
            'sku_id' => 6,
            'title' => 'PP单面隔膜压力表7KG',
            'unit' => '个',
            'quantity' => '50',
            'price' => 65,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'goods_id' => 8,
            'sku_id' => 22,
            'title' => 'kingspring流量计011 150JPM',
            'unit' => '个',
            'quantity' => '10',
            'price' => 350,
        ]);
    }
}