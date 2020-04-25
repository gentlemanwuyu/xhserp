<?php

namespace App\Listeners;

use App\Events\Entried;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Purchase\Models\PurchaseOrder;

class EntriedPurchaseOrderListener implements ShouldQueue
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

            $purchase_order = $entry->purchaseOrderItem->purchaseOrder;

            $is_finished = true;
            foreach ($purchase_order->items as $purchase_order_item) {
                if (0 < $purchase_order_item->pending_entry_quantity) {
                    $is_finished = false;
                }
            }

            if ($is_finished) {
                $purchase_order->status = PurchaseOrder::FINISHED;
                $purchase_order->save();
                Log::info("[EntriedPurchaseOrderListener]采购订单[{$purchase_order->id}]已全部完成入库，状态改为4.");
            }

            // 判断订单的exchange_status字段是否要修改为0（即是否已完成换货）
            $is_exchanged = true;
            foreach ($purchase_order->egressExchangeReturnOrders as $purchaseReturnOrder) {
                foreach ($purchaseReturnOrder->items as $pro_item) {
                    if ($pro_item->quantity > $pro_item->entried_quantity) {
                        // 如果换货Item的数量大于入库数量，说明还没出完货
                        $is_exchanged = false;
                        break 2; // 直接终止订单的循环
                    }
                }
            }
            if ($is_exchanged) {
                $purchase_order->exchange_status = 0;
                $purchase_order->save();
                Log::info("采购订单[{$purchase_order->id}]已经完成换货，换货状态改为[0]");
            }
        }catch (\Exception $e) {
            Log::info("[EntriedPurchaseOrderListener]事件发生异常:" . $e->getMessage());
            throw new \Exception("系统内部出错，请联系程序猿！");
        }
    }
}
