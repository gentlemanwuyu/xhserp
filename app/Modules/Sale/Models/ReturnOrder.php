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

class ReturnOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhsro';

    static $statuses = [
        1 => '待审核',
        2 => '已驳回',
        3 => '已通过',
        4 => '已入库',
        5 => '已完成',
        6 => '已取消',
    ];

    static $methods = [
        1 => '换货',
        2 => '退货',
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

    /**
     * 退货方式为退货的抵扣逻辑（财务方面）
     *
     * @return $this
     */
    public function backDeduction()
    {
        if (4 != $this->status || 2 != $this->method) {
            throw new \Exception("退货单[{$this->id}]退货状态不是已入库或者退货方式不是\"退货\"");
        }

        // 首先，看看有无相同的已出货Item可以抵扣（发货早的先抵扣）
        $is_finished = true;
        foreach ($this->items as $roi) {
            $remained_back_quantity = $roi->quantity;
            $order_item = $roi->orderItem;
            foreach ($order_item->payableDeliveryItems as $payableDeliveryItem) {
                if ($remained_back_quantity <= 0) {
                    break;
                }
                if ($payableDeliveryItem->real_quantity >= $remained_back_quantity) {
                    // 如果发货单Item的数量大于等于剩余退货数量，则减去退货数量
                    DeliveryOrderItemBack::create([
                        'delivery_order_item_id' => $payableDeliveryItem->id,
                        'return_order_item_id' => $roi->id,
                        'quantity' => $remained_back_quantity,
                    ]);
                    $remained_back_quantity = 0;
                }else {
                    // 如果发货单Item的数量小于剩余退货数量，则全部用于退货，剩余退货数量减去这个Item的真实数量，进行下一次循环
                    DeliveryOrderItemBack::create([
                        'delivery_order_item_id' => $payableDeliveryItem->id,
                        'return_order_item_id' => $roi->id,
                        'quantity' => $payableDeliveryItem->real_quantity,
                    ]);
                    $remained_back_quantity -= $payableDeliveryItem->real_quantity;
                }
            }

            // 如果还有剩余退货数量未抵扣，退货单的状态就不能改为已完成
            if (0 < $remained_back_quantity) {
                $is_finished = false;
            }
        }

        if ($is_finished) {
            $this->status = 5;
            $this->save();
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

    /**
     * 处理日志
     *
     * @return mixed
     */
    public function handleLog()
    {
        return $this->hasOne(ReturnOrderLog::class)->where('action', 3)->orderBy('id', 'desc');
    }
}