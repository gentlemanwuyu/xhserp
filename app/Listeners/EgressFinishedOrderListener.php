<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/2/13
 * Time: 21:15
 */

namespace App\Listeners;

use App\Events\EgressFinished;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\DeliveryOrder;
use App\Modules\Sale\Models\DeliveryOrderItem;

class EgressFinishedOrderListener implements ShouldQueue
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
     * @param EgressFinished $event
     */
    public function handle(EgressFinished $event)
    {
        try {
            $order_ids = DeliveryOrderItem::where('delivery_order_id', $event->delivery_order_id)->pluck('order_id')->toArray();
            $order_ids = array_unique($order_ids);
            foreach ($order_ids as $order_id) {
                $order = Order::find($order_id);
                $is_finished = true;
                foreach ($order->items as $item) {
                    // 如果已出货数量 - 已入库的换货数量 < 订单Item的数量，则说明还没完成出货
                    if ($item->deliveried_quantity - $item->entry_exchange_quantity < $item->quantity) {
                        $is_finished = false;
                        break;
                    }
                }

                if ($is_finished) {
                    $order->status = 4;
                    $order->save();
                    Log::info("订单[{$order->id}]已经完成出货，状态改为[4]");
                }
            }
        }catch (\Exception $e) {
            Log::info("[EgressFinishedOrderListener]事件发生异常:" . $e->getMessage());
            throw new \Exception("系统内部出错，请联系程序猿！");
        }
    }
}