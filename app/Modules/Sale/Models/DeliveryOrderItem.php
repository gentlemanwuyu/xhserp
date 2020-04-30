<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/17
 * Time: 19:49
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderItem extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 分配数量(换货优先)
     *
     * @return $this
     */
    public function assignQuantity()
    {
        $pending_exchange_items = $this->orderItem->pendingExchangeItems;
        $remained_quantity = $this->quantity;
        // 先抵扣换货数量
        while($pending_exchange_item = $pending_exchange_items->shift()) {
            // 待换货数量
            $pending_exchange_quantity = $pending_exchange_item->quantity - $pending_exchange_item->delivery_quantity;
            if ($pending_exchange_quantity > $remained_quantity) {
                $pending_exchange_item->delivery_quantity += $remained_quantity;
                $pending_exchange_item->save();
                DeliveryOrderItemExchange::create([
                    'delivery_order_item_id' => $this->id,
                    'return_order_item_id' => $pending_exchange_item->id,
                    'quantity' => $remained_quantity,
                ]);
                $remained_quantity = 0;
            }else {
                $pending_exchange_item->delivery_quantity += $pending_exchange_quantity;
                $pending_exchange_item->save();
                DeliveryOrderItemExchange::create([
                    'delivery_order_item_id' => $this->id,
                    'return_order_item_id' => $pending_exchange_item->id,
                    'quantity' => $pending_exchange_quantity,
                ]);
                $remained_quantity -= $pending_exchange_quantity;
            }
            // 如果还有数量剩下，则继续循环抵扣
            if (0 >= $remained_quantity) {
                break;
            }
        }
        // 如果抵扣完还有数量剩下，那么这个就是真实的出货数量
        if (0 < $remained_quantity) {
            $this->real_quantity = $remained_quantity;
            $this->save();
        }

        return $this;
    }

    public function getIsPaidNameAttribute()
    {
        return YES == $this->is_paid ? '是' : '否';
    }
}