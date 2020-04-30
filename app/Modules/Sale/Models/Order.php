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

    // 订单状态
    const PENDING_REVIEW = 1;
    const REJECTED = 2;
    const AGREED = 3;
    const FINISHED = 4;
    const CANCELED = 5;
    static $statuses = [
        self::PENDING_REVIEW    => '待审核',
        self::REJECTED          => '已驳回',
        self::AGREED            => '已通过',
        self::FINISHED          => '已完成',
        self::CANCELED          => '已取消',
    ];

    // 付款状态
    const PENDING_PAYMENT = 1; // 待付款
    const FINISHED_PAYMENT = 2; // 完成付款
    static $payment_statuses = [
        self::PENDING_PAYMENT => '待付款',
        self::FINISHED_PAYMENT => '完成付款',
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

    public function logs()
    {
        return $this->hasMany(OrderLog::class)->orderBy('id', 'desc');
    }

    /**
     * 已入库的换货退货单
     *
     * @return mixed
     */
    public function entryExchangeReturnOrders()
    {
        return $this->hasMany(ReturnOrder::class)
            ->where('method', ReturnOrder::EXCHANGE)
            ->where('status', ReturnOrder::ENTRIED)
            ->OrderBy('id', 'desc');
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

    public function getPaymentStatusNameAttribute()
    {
        return isset(self::$payment_statuses[$this->payment_status]) ? self::$payment_statuses[$this->payment_status] : '';
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

    /**
     * 是否可删除
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        // 有出货记录的订单不可删除
        if (DeliveryOrderItem::where('order_id', $this->id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * 是否可取消
     *
     * @return bool
     */
    public function getCancelableAttribute()
    {
        // 如果是可删除的，则不可取消
        if ($this->deletable) {
            return false;
        }

        // 有待审核或待出货的出货单不可取消
        $pending_delivery_order_exists = DeliveryOrderItem::leftJoin('delivery_orders AS do', 'do.id', '=', 'delivery_order_items.delivery_order_id')
            ->whereNull('do.deleted_at')
            ->where('delivery_order_items.order_id', $this->id)
            ->whereIn('do.status', [DeliveryOrder::PENDING_REVIEW, DeliveryOrder::PENDING_DELIVERY])
            ->exists();

        if ($pending_delivery_order_exists) {
            return false;
        }

        return true;
    }
}