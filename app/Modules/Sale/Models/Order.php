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
use App\Traits\CodeTrait;
use App\Models\Currency;
use App\Modules\Index\Models\User;
use App\Modules\Finance\Models\DeliveryOrderItemDeduction;

class Order extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhsso';

    static $statuses = [
        1 => '待审核',
        2 => '已驳回',
        3 => '已通过',
        4 => '已完成',
        5 => '已取消',
    ];

    public function syncItems($items)
    {
        if (!$items || !is_array($items)) {
            return false;
        }

        // 将不在请求中的item删除
        OrderItem::where('order_id', $this->id)->whereNotIn('id', array_keys($items))->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($items as $flag => $item) {
            $item_data = [
                'goods_id' => $item['goods_id'],
                'sku_id' => $item['sku_id'],
                'title' => $item['title'],
                'unit' => $item['unit'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'note' => $item['note'],
            ];

            $order_item = OrderItem::find($flag);

            if (!$order_item) {
                $item_data['order_id'] = $this->id;
                OrderItem::create($item_data);
            }else {
                $order_item->update($item_data);
            }
        }

        return $this;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function deliveryItems()
    {
        return $this->hasMany(DeliveryOrderItem::class)->OrderBy('id', 'desc');
    }

    public function returnOrders()
    {
        return $this->hasMany(ReturnOrder::class)->OrderBy('id', 'desc');
    }

    public function entryExchangeReturnOrders()
    {
        return $this->hasMany(ReturnOrder::class)->where('method', 1)->where('status', 4)->OrderBy('id', 'desc');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaymentMethodNameAttribute()
    {
        return isset(Customer::$payment_methods[$this->payment_method]) ? Customer::$payment_methods[$this->payment_method] : '';
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getTaxNameAttribute()
    {
        return isset(Customer::$taxes[$this->tax]) ? Customer::$taxes[$this->tax]['display'] : '';
    }

    /**
     * 订单是否需要出货
     *
     * @return bool
     */
    public function getDeliverableAttribute()
    {
        $deliverable = false;

        $this->items->each(function ($order_item) use (&$deliverable) {
            if (0 < $order_item->pending_delivery_quantity) {
                $deliverable = true;
                return false;
            }
        });

        return $deliverable;
    }

    /**
     * 订单是否可退货
     *
     * @return bool
     */
    public function getReturnableAttribute()
    {
        return !$this->items->filter(function ($item) {
            return $item->returnable_quantity;
        })->isEmpty();
    }

    /**
     * 真实付款金额
     *
     * @return number
     */
    public function getRealPaidAmountAttribute()
    {
        $real_paids = DeliveryOrderItemDeduction::leftJoin('delivery_order_items AS doi', 'doi.id', '=', 'delivery_order_item_deductions.delivery_order_item_id')
            ->where('doi.order_id', $this->id)
            ->where('delivery_order_item_deductions.collection_id', '>', 0)
            ->pluck('delivery_order_item_deductions.amount');

        return array_sum($real_paids->toArray());
    }
}