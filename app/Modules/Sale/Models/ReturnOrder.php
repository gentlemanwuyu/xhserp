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
        4 => '已完成',
        5 => '已取消',
    ];

    static $methods = [
        1 => '换货',
        2 => '退货(货款下次抵扣)',
        3 => '退货退款',
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
                $item_data['return_order_id'] = $this->id;
                $item = ReturnOrderItem::create($item_data);
            }else {
                $item->update($item_data);
            }

            $order_item = $item->orderItem;
            // 判断是否超出已出货数量
            if ($order_item->deliveried_quantity < $item->quantity) {
                throw new \Exception("[{$order_item->title}]退货数量不可超出已出货数量");
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