<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/8
 * Time: 14:20
 */

namespace App\Listeners;

use App\Events\Egressed;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Sale\Models\ReturnOrderItem;
use App\Modules\Sale\Models\DeliveryOrderItemExchange;

class EgressedReturnOrderListener implements ShouldQueue
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
            $return_order_item_ids = DeliveryOrderItemExchange::leftJoin('delivery_order_items AS doi', 'doi.id', '=','delivery_order_item_exchanges.delivery_order_item_id')
                ->where('doi.delivery_order_id', $event->delivery_order_id)
                ->pluck('delivery_order_item_exchanges.return_order_item_id')
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
            Log::info("[EgressedReturnOrderListener]事件发生异常:" . $e->getMessage());
            throw new \Exception("系统内部出错，请联系程序猿！");
        }
    }
}