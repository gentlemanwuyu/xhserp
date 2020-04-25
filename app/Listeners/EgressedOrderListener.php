<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/8
 * Time: 14:19
 */

namespace App\Listeners;

use App\Events\Egressed;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\DeliveryOrderItem;

class EgressedOrderListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Egressed $event
     */
    public function handle(Egressed $event)
    {
        try {
            $order_ids = DeliveryOrderItem::where('delivery_order_id', $event->delivery_order_id)->pluck('order_id')->toArray();
            $order_ids = array_unique($order_ids);
            foreach ($order_ids as $order_id) {
                $order = Order::find($order_id);
                // 判断是否已完成出货
                $is_finished = true;
                foreach ($order->items as $item) {
                    // 如果已出货数量 - 已入库的换货数量 < 订单Item的数量，则说明还没完成出货
                    if ($item->deliveried_quantity - $item->entry_exchange_quantity < $item->quantity) {
                        $is_finished = false;
                        break;
                    }
                }

                if ($is_finished) {
                    $order->status = Order::FINISHED;
                    $order->save();
                    Log::info("订单[{$order->id}]已经完成出货，状态改为[4]");
                }

                // 判断订单的exchange_status字段是否要修改为0（即是否已完成换货）
                $is_exchanged = true;
                foreach ($order->entryExchangeReturnOrders as $returnOrder) {
                    foreach ($returnOrder->items as $roi) {
                        if ($roi->quantity > $roi->delivery_quantity) {
                            // 如果换货Item的数量大于出货数量，说明还没出完货
                            $is_exchanged = false;
                            break 2; // 直接终止订单的循环
                        }
                    }
                }
                if ($is_exchanged) {
                    $order->exchange_status = 0;
                    $order->save();
                    Log::info("订单[{$order->id}]已经完成换货，换货状态改为[0]");
                }
            }
        }catch (\Exception $e) {
            Log::info("[EgressedOrderListener]事件发生异常:" . $e->getMessage());
            throw new \Exception("系统内部出错，请联系程序猿！");
        }
    }
}