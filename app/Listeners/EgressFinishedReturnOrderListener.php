<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/3/8
 * Time: 12:58
 */

namespace App\Listeners;

use App\Events\EgressFinished;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Sale\Models\ReturnOrderItem;
use App\Modules\Sale\Models\DeliveryOrderItem;

class EgressFinishedReturnOrderListener implements ShouldQueue
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
            $return_order_item_ids = DeliveryOrderItem::leftJoin('delivery_order_item_exchanges AS doie', 'doie.delivery_order_item_id', '=', 'delivery_order_items.id')
                ->where('delivery_order_items.delivery_order_id', $event->delivery_order_id)
                ->pluck('doie.return_order_item_id')
                ->toArray();
            $return_order_item_ids = array_unique($return_order_item_ids);
            $return_order_ids = [];
            foreach ($return_order_item_ids as $roi_id) {
                $returnOrderItem = ReturnOrderItem::find($roi_id);
                $returnOrder = $returnOrderItem->returnOrder;
                if (!$returnOrder) {
                    throw new \Exception("退货单Item[{$roi_id}]找不到退货单");
                }
                if (1 != $returnOrder->method) {
                    throw new \Exception("退货单[{$returnOrder->id}]的退货方式不是换货");
                }
                if (in_array($returnOrderItem->return_order_id, $return_order_ids)) {
                    continue;
                }
                $return_order_ids[] = $returnOrderItem->return_order_id;
                $is_finished = true;
                foreach ($returnOrder->items as $item) {
                    if ($item->quantity > $item->delivery_quantity) {
                        $is_finished = false;
                        break;
                    }
                }
                if ($is_finished) {
                    $returnOrder->status = 5;
                    $returnOrder->save();
                    Log::info("退货单[{$returnOrder->id}]已经完成，状态改为[5]");
                }
            }
        }catch (\Exception $e) {
            Log::info("[EgressFinishedReturnOrderListener]事件发生异常:" . $e->getMessage());
        }
    }
}