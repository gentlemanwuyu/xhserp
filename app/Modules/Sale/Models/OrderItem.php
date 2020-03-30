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
use Illuminate\Support\Facades\DB;
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
     * 待换货Item
     *
     * @return mixed
     */
    public function pendingExchangeItems()
    {
        return $this->hasMany(ReturnOrderItem::class)
            ->leftJoin('return_orders AS ro', 'ro.id', '=', 'return_order_items.return_order_id')
            ->where('ro.method', 1)
            ->where('ro.status', 4)
            ->whereRaw('return_order_items.quantity > return_order_items.delivery_quantity')
            ->select(['return_order_items.*'])
            ->orderBy('return_order_items.id', 'asc');
    }

    /**
     * 需要付款的发货单Item
     *
     * @return mixed
     */
    public function payableDeliveryItems()
    {
        return $this->hasMany(DeliveryOrderItem::class)
            ->leftJoin('delivery_orders AS do', 'do.id', '=', 'delivery_order_items.delivery_order_id')
            ->where('do.status', 2)
            ->where('delivery_order_items.is_paid', 0)
            ->where('delivery_order_items.real_quantity', '>', 0)
            ->select([
                'delivery_order_items.*',
            ])
            ->orderBy('delivery_order_items.id', 'asc');

    }

    /**
     * 出货数量，包含已出货的和待出货的
     *
     * @return int|number
     */
    public function getDeliveryQuantityAttribute()
    {
        $deliveryItems = $this->deliveryItems;

        return $deliveryItems->isEmpty() ? 0 : array_sum($deliveryItems->pluck('quantity')->toArray());
    }

    /**
     * 待出货数量
     *
     * @return mixed
     */
    public function getPendingDeliveryQuantityAttribute()
    {
        // 订单数量 + 已入库的换货数量 - 出货数量
        return $this->quantity + $this->entry_exchange_quantity - $this->delivery_quantity;
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
     * 已入库的换货数量
     *
     * @return number
     */
    public function getEntryExchangeQuantityAttribute()
    {
        $exchange_items = ReturnOrder::leftJoin('return_order_items AS roi', 'roi.return_order_id', '=', 'return_orders.id')
            ->where('roi.order_item_id', $this->id)
            ->where('return_orders.method', 1)
            ->whereIn('return_orders.status', [4, 5])
            ->pluck('roi.quantity');

        return array_sum($exchange_items->toArray());
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