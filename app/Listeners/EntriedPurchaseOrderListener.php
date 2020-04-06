<?php

namespace App\Listeners;

use App\Events\Entried;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Modules\Warehouse\Models\SkuEntry;
use App\Modules\Purchase\Models\PurchaseOrderItem;

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

            $purchase_order_item = $entry->purchaseOrderItem;
            $purchase_order = $purchase_order_item->purchaseOrder;

            // 检查是否已经全部入库
            $entry_quantities = SkuEntry::where('purchase_order_item_id', $entry->purchase_order_item_id)->pluck('quantity')->toArray();
            $entried_quantity = array_sum($entry_quantities);
            if ($entried_quantity >= $purchase_order_item->quantity) {
                $purchase_order_item->delivery_status = 2;
                $purchase_order_item->save();
            }

            // 检查订单是否已完成
            $unfinished_order_items = PurchaseOrderItem::where('purchase_order_id', $purchase_order_item->purchase_order_id)->where('delivery_status', '!=', 2)->get(['id', 'purchase_order_id', 'delivery_status']);
            if ($unfinished_order_items->isEmpty()) {
                $purchase_order->status = 4;
                $purchase_order->save();
                Log::info("[EntriedPurchaseOrderListener]采购订单[{$purchase_order->id}]已全部完成入库，状态改为4.");
            }
        }catch (\Exception $e) {
            Log::info("[EntriedPurchaseOrderListener]事件发生异常:" . $e->getMessage());
            throw new \Exception("系统内部出错，请联系程序猿！");
        }
    }
}
