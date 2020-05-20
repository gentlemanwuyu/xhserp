<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/11
 * Time: 12:04
 */

namespace App\Modules\Sale\Database\Seeds;

use App\Modules\Goods\Models\Goods;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\OrderItem;
use App\Modules\Sale\Models\Customer;
use App\Modules\Finance\Models\Collection;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $customer_number = $customers->count();
        $goods = Goods::all();
        $this_year = Carbon::now()->year;
        $this_month = Carbon::now()->month;
        $last_year = $this_year -1 ;

        // 去年各个月份的订单
        foreach (range(1, 12) as $month) {
            // 业绩在60万到100万之间随机取
            $amount = rand(600000, 1000000);
            foreach ($customers->shuffle() as $customer) {
                // 每个客户平均订单金额
                $order_amount = price_format($amount / $customer_number);
                $carbon = Carbon::create($last_year, $month, rand(1, 28));
                $clone_carbon = clone $carbon;
                $order = Order::create([
                    'code' => Order::codeGenerator($carbon->toDateString()),
                    'customer_id' => $customer->id,
                    'payment_method' => $customer->payment_method,
                    'tax' => $customer->tax,
                    'currency_code' => $customer->currency_code,
                    'delivery_date' => $clone_carbon->addDays(rand(3, 7))->toDateString(),
                    'status' => Order::FINISHED,
                    'payment_status' => Order::FINISHED_PAYMENT,
                    'user_id' => $customer->manager_id,
                    'created_at' => $carbon->toDateTimeString(),
                    'updated_at' => $carbon->toDateTimeString(),
                ]);

                $order_item_number = rand(1, 5);
                $item_amount = price_format($order_amount / $order_item_number); // 每个Item平均金额
                foreach ($goods->shuffle()->slice(0, $order_item_number)  as $g) {
                    $goods_sku = $g->skus->shuffle()->shift(); // 随机取一个sku
                    $price = price_format($goods_sku->lowest_price * 1.3);
                    $quantity = floor($item_amount / $price);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'goods_id' => $g->id,
                        'sku_id' => $goods_sku->id,
                        'title' => $g->name,
                        'unit' => '个',
                        'quantity' => $quantity,
                        'price' => $price,
                        'created_at' => $carbon->toDateTimeString(),
                        'updated_at' => $carbon->toDateTimeString(),
                    ]);
                }

                $collection_amount = price_format($order_amount * rand(60, 100) / 100);
                Collection::create([
                    'customer_id' => $customer->id,
                    'amount' => $collection_amount,
                    'method' => \Payment::REMITTANCE,
                    'currency_code' => $customer->currency_code,
                    'account_id' => 2,
                    'remained_amount' => $collection_amount,
                    'user_id' => rand(2, 31),
                    'created_at' => $carbon->toDateTimeString(),
                    'updated_at' => $carbon->toDateTimeString(),
                ]);
            }
        }

        // 今年各个月份的订单
        foreach (range(1, $this_month) as $month) {
            // 业绩在60万到100万之间随机取
            $amount = rand(800000, 1300000);
            foreach ($customers->shuffle() as $customer) {
                // 每个客户平均订单金额
                $order_amount = price_format($amount / $customer_number);
                $carbon = Carbon::create($this_year, $month, rand(1, 28));
                $clone_carbon = clone $carbon;
                $order = Order::create([
                    'code' => Order::codeGenerator($carbon->toDateString()),
                    'customer_id' => $customer->id,
                    'payment_method' => $customer->payment_method,
                    'tax' => $customer->tax,
                    'currency_code' => $customer->currency_code,
                    'delivery_date' => $clone_carbon->addDays(rand(3, 7))->toDateString(),
                    'status' => Order::FINISHED,
                    'payment_status' => Order::FINISHED_PAYMENT,
                    'user_id' => $customer->manager_id,
                    'created_at' => $carbon->toDateTimeString(),
                    'updated_at' => $carbon->toDateTimeString(),
                ]);

                $order_item_number = rand(1, 5);
                $item_amount = price_format($order_amount / $order_item_number); // 每个Item平均金额
                foreach ($goods->shuffle()->slice(0, $order_item_number)  as $g) {
                    $goods_sku = $g->skus->shuffle()->shift(); // 随机取一个sku
                    $price = price_format($goods_sku->lowest_price * 1.3);
                    $quantity = floor($item_amount / $price);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'goods_id' => $g->id,
                        'sku_id' => $goods_sku->id,
                        'title' => $g->name,
                        'unit' => '个',
                        'quantity' => $quantity,
                        'price' => $price,
                        'created_at' => $carbon->toDateTimeString(),
                        'updated_at' => $carbon->toDateTimeString(),
                    ]);
                }
                $collection_amount = price_format($order_amount * rand(60, 100) / 100);
                Collection::create([
                    'customer_id' => $customer->id,
                    'amount' => $collection_amount,
                    'method' => \Payment::REMITTANCE,
                    'currency_code' => $customer->currency_code,
                    'account_id' => 2,
                    'remained_amount' => $collection_amount,
                    'user_id' => rand(2, 31),
                    'created_at' => $carbon->toDateTimeString(),
                    'updated_at' => $carbon->toDateTimeString(),
                ]);
            }
        }
    }
}