<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/2/15
 * Time: 23:46
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Index\Models\User;
use App\Traits\CodeTrait;
use App\Modules\Finance\Models\DeliveryOrderItemDeduction;

class ReturnOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhsro';

    // 退货单状态
    const PENDING_REVIEW = 1;
    const REJECTED = 2;
    const AGREED = 3;
    const ENTRIED = 4;
    const FINISHED = 5;
    const CANCELED = 6;
    static $statuses = [
        self::PENDING_REVIEW    => '待审核',
        self::REJECTED          => '已驳回',
        self::AGREED            => '已通过',
        self::ENTRIED           => '已入库',
        self::FINISHED          => '已完成',
        self::CANCELED          => '已取消',
    ];

    // 退货方式
    const EXCHANGE = 1;
    const BACK = 2;
    static $methods = [
        self::EXCHANGE  => '换货',
        self::BACK      => '退货',
    ];

    public function syncItems($items)
    {
        if (!$items || !is_array($items)) {
            return false;
        }

        // 将不在请求中的item删除
        ReturnOrderItem::where('return_order_id', $this->id)->whereNotIn('id', array_keys($items))->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($items as $flag => $item) {
            $item_data = array_only($item, [
                'order_item_id',
                'quantity',
                'received_quantity',
                'entry_quantity',
            ]);

            $item = ReturnOrderItem::find($flag);

            if (!$item) {
                // 判断是否超出已出货数量
                $order_item = OrderItem::find($item_data['order_item_id']);
                if ($order_item->returnable_quantity < $item_data['quantity']) {
                    throw new \Exception("[{$order_item->title}]退货数量[{$item_data['quantity']}]不可超出可退数量[{$order_item->returnable_quantity}]");
                }

                $item_data['return_order_id'] = $this->id;
                $item = ReturnOrderItem::create($item_data);
            }else {
                // 判断是否超出已出货数量
                if (!empty($item_data['quantity'])) {
                    $order_item = $item->orderItem;
                    $returnable_quantity = $order_item->returnable_quantity + $item->quantity;
                    if ($item_data['quantity'] > $returnable_quantity) {
                        throw new \Exception("[{$order_item->title}]退货数量[{$item_data['quantity']}]不可超出可退数量[{$returnable_quantity}]");
                    }
                }
                $item->update($item_data);
            }
        }

        return $this;
    }

    public function items()
    {
        return $this->hasMany(ReturnOrderItem::class);
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getMethodNameAttribute()
    {
        return isset(self::$methods[$this->method]) ? self::$methods[$this->method] : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function logs()
    {
        return $this->hasMany(ReturnOrderLog::class)->orderBy('id', 'desc');
    }

    /**
     * 处理日志
     *
     * @return mixed
     */
    public function handleLog()
    {
        return $this->hasOne(ReturnOrderLog::class)->where('action', ReturnOrderLog::HANDLE)->orderBy('id', 'desc');
    }

    /**
     * 退货单金额(人民币)
     *
     * @return int
     */
    public function getAmountAttribute()
    {
        $amount = 0;
        foreach ($this->items as $roi) {
            $order_item = $roi->orderItem;
            $order = $order_item->order;
            $currency = $order->currency;
            $amount += $roi->quantity * $order_item->price * $currency->rate;
        }

        return $amount;
    }

    /**
     * 未抵扣金额
     *
     * @return mixed
     */
    public function getUndeductedAmountAttribute()
    {
        $deductions = DeliveryOrderItemDeduction::where('return_order_id', $this->id)->pluck('amount')->toArray();

        return $deductions ? $this->amount - array_sum($deductions) : $this->amount;
    }
}