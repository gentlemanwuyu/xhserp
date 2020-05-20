<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/17
 * Time: 20:42
 */

namespace App\Modules\Sale\Database\Seeds;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\DeliveryOrder;
use App\Modules\Sale\Models\DeliveryOrderItem;

class DeliveryOrdersTableSeeder extends Seeder
{
    public function run()
    {
        foreach (Order::all() as $order) {
            $carbon = Carbon::parse($order->delivery_date)->addDay();
            $customer = $order->customer;
            $contacts = $customer->contacts;
            $contact = $contacts->shuffle()->shift();
            $delivery_methods = DeliveryOrder::$delivery_methods;
            shuffle($delivery_methods);
            $delivery_method = array_shift($delivery_methods);
            $data = [
                'code' => DeliveryOrder::codeGenerator($carbon->toDateString()),
                'customer_id' => $customer->id,
                'delivery_method' => $delivery_method,
                'address' => $customer->full_address,
                'consignee' => $contact->name,
                'consignee_phone' => $contact->phone ?: '13800138000',
                'status' => DeliveryOrder::FINISHED,
                'user_id' => $customer->manager_id,
                'created_at' => $carbon->toDateTimeString(),
                'updated_at' => $carbon->toDateTimeString(),
            ];

            if (DeliveryOrder::EXPRESS == $delivery_method) {
                $data['express_id'] = rand(1, 4);
            }
            $delivery_order = DeliveryOrder::create($data);
            foreach ($order->items as $order_item) {
                DeliveryOrderItem::create([
                    'delivery_order_id' => $delivery_order->id,
                    'order_id' => $order->id,
                    'order_item_id' => $order_item->id,
                    'title' => $order_item->title,
                    'quantity' => $order_item->quantity,
                    'real_quantity' => $order_item->quantity,
                    'is_paid' => YES,
                    'delivered_at' => $carbon->toDateString() . ' 23:59:59',
                    'created_at' => $carbon->toDateTimeString(),
                    'updated_at' => $carbon->toDateTimeString(),
                ]);
            }
        }
    }
}