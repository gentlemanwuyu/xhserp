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