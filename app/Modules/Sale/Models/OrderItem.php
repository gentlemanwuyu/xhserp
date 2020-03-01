<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/11
 * Time: 11:59
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Goods\Models\Goods;
use App\Modules\Goods\Models\GoodsSku;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function sku()
    {
        return $this->belongsTo(GoodsSku::class, 'sku_id');
    }

    /**
     * 关联发货Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveryItems()
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    /**
     * 待发货数量
     *
     * @return mixed
     */
    public function getPendingDeliveryQuantityAttribute()
    {
        $quantity = 0;
        foreach ($this->deliveryItems as $deliveryItem) {
            $quantity += $deliveryItem->quantity;
        }

        return $this->quantity - $quantity;
    }

    /**
     * 已完成出货的数量
     *
     * @return number
     */
    public function getDeliveriedQuantityAttribute()
    {
        $deliveriedItems = DeliveryOrder::leftJoin('delivery_order_items AS doi', 'doi.delivery_order_id', '=', 'delivery_orders.id')
            ->where('delivery_orders.status', 2)
            ->where('doi.order_item_id', $this->id)
            ->pluck('quantity');

        return array_sum($deliveriedItems->toArray());
    }

    /**
     * 可退数量
     *
     * @return int
     */
    public function getReturnableQuantityAttribute()
    {
        $returned_items = ReturnOrderItem::where('order_item_id', $this->id)->pluck('quantity');

        $returned_quantity = array_sum($returned_items->toArray());

        return $this->deliveried_quantity - $returned_quantity;
    }

    /**
     * 已付款数量
     *
     * @return int
     */
    public function getPaidQuantityAttribute()
    {
        $quantity = 0;

        foreach ($this->deliveryItems as $delivery_item) {
            if (1 == $delivery_item->is_paid) {
                $quantity = $quantity + $delivery_item->quantity;
            }
        }

        return $quantity;
    }
}