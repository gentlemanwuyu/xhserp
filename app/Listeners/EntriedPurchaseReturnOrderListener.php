<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/8
 * Time: 13:09
 */

namespace App\Listeners;

use App\Events\Entried;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Purchase\Models\PurchaseReturnOrderItem;

class EntriedPurchaseReturnOrderListener implements ShouldQueue
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
     * @param  Entried  $event
     * @return void
     */
    public function handle(Entried $event)
    {
        try {
            $entry = SkuEntry::find($event->entry_id);
            if (!$entry) {
                throw new \Exception("找不到对应的入库记录, entry_id:{$event->entry_id}");
            }

            $purchase_return_order_ids = [];
            foreach ($entry->exchanges as $exchange) {
                $purchase_return_order_item = PurchaseReturnOrderItem::find($exchange->purchase_return_order_item_id);
                $purchase_return_order = $purchase_return_order_item->purchaseReturnOrder;
                if (!$purchase_return_order) {
                    throw new \Exception("采购退货单Item[{$purchase_return_order_item->id}]找不到退货单");
                }
                if (1 != $purchase_return_order->method) {
                    throw new \Exception("采购退货单[{$purchase_return_order->id}]的退货方式不是换货");
                }
                if (in_array($purchase_return_order->id, $purchase_return_order_ids)) {
                    continue;
                }
                $purchase_return_order_ids[] = $purchase_return_order->id;
                $is_finished = true;
                foreach ($purchase_return_order->items as $item) {
                    if ($item->quantity > $item->entried_quantity) {
                        $is_finished = false;
                        break;
                    }
                }
                if ($is_finished) {
                    $purchase_return_order->status = 3;
                    $purchase_return_order->save();
                    Log::info("采购退货单[{$purchase_return_order->id}]已经完成，状态改为[5]");
                }
            }

        }catch (\Exception $e) {
            Log::info("[EntriedPurchaseReturnOrderListener]事件发生异常:" . $e->getMessage());
            throw new \Exception("系统内部出错，请联系程序猿！");
        }
    }
}