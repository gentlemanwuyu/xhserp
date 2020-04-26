<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/17
 * Time: 20:42
 */

namespace App\Modules\Sale\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Sale\Models\DeliveryOrder;
use App\Modules\Sale\Models\DeliveryOrderItem;

class DeliveryOrdersTableSeeder extends Seeder
{
    public function run()
    {
        $delivery_order = DeliveryOrder::create([
            'code' => 'xhsdo20191217001',
            'customer_id' => 2,
            'delivery_method' => DeliveryOrder::EXPRESS,
            'express_id' => 4,
            'is_collected' => YES,
            'collected_amount' => '',
            'address' => '江苏省苏州市太仓市万盛公司',
            'consignee' => '小饶',
            'consignee_phone' => '15206226661',
            'status' => DeliveryOrder::PENDING_DELIVERY,
            'user_id' => 7,
        ]);
        DeliveryOrderItem::create([
            'delivery_order_id' => $delivery_order->id,
            'order_id' => 3,
            'order_item_id' => 6,
            'title' => 'PP单面隔膜压力表10KG',
            'quantity' => 20,
        ]);
        DeliveryOrderItem::create([
            'delivery_order_id' => $delivery_order->id,
            'order_id' => 3,
            'order_item_id' => 7,
            'title' => 'kingspring流量计011 150JPM',
            'quantity' => 10,
        ]);

        $delivery_order = DeliveryOrder::create([
            'code' => 'xhsdo20191217002',
            'customer_id' => 3,
            'delivery_method' => DeliveryOrder::SEND,
            'express_id' => 3,
            'address' => '广东省深圳市宝安区西乡街道铁岗水库路166号(桃花源科技创业中心侧)',
            'consignee' => '王先生',
            'consignee_phone' => '13800138000',
            'status' => DeliveryOrder::PENDING_DELIVERY,
            'user_id' => 8,
        ]);
        DeliveryOrderItem::create([
            'delivery_order_id' => $delivery_order->id,
            'order_id' => 2,
            'order_item_id' => 3,
            'title' => 'PP单面隔膜压力表10KG',
            'quantity' => 40,
        ]);
        DeliveryOrderItem::create([
            'delivery_order_id' => $delivery_order->id,
            'order_id' => 2,
            'order_item_id' => 4,
            'title' => 'kingspring流量计011 200JPM',
            'quantity' => 10,
        ]);
        DeliveryOrderItem::create([
            'delivery_order_id' => $delivery_order->id,
            'order_id' => 2,
            'order_item_id' => 5,
            'title' => '孟山都滚轮片162A',
            'quantity' => 300,
        ]);
    }
}