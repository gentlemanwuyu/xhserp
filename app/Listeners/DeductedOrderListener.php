<?php

namespace App\Listeners;

use App\Events\Deducted;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Sale\Models\Order;
use App\Modules\Sale\Models\DeliveryOrderItem;

class DeductedOrderListener implements ShouldQueue
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
     * @param  Deducted  $event
     * @return void
     */
    public function handle(Deducted $event)
    {
        foreach ($event->delivery_order_item_ids as $doi_id) {
            $delivery_order_item = DeliveryOrderItem::find($doi_id);
            $order = $delivery_order_item->order;
            // 如果订单的支付状态为完成付款，则跳过
            if (Order::FINISHED_PAYMENT == $order->payment_status) {
                continue;
            }

            $is_finished = true;
            foreach ($order->deliveryItems as $delivery_order_item) {
                if (0 < $delivery_order_item->real_quantity && NO == $delivery_order_item->is_paid) {
                    $is_finished = false;
                }
            }

            if ($is_finished) {
                $order->payment_status = Order::FINISHED_PAYMENT;
                $order->save();
            }
        }
    }
}
