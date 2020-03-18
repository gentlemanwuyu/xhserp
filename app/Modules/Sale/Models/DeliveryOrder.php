<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/17
 * Time: 19:45
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Index\Models\User;
use App\Modules\Warehouse\Models\Express;
use App\Traits\CodeTrait;

class DeliveryOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhssdo';

    static $delivery_methods = [
        1 => '客户自取',
        2 => '送货',
        3 => '快递物流',
    ];

    static $statuses = [
        1 => '待出货',
        2 => '已完成',
    ];

    public function syncItems($items)
    {
        if (!$items || !is_array($items)) {
            return false;
        }

        // 将不在请求中的item删除
        DeliveryOrderItem::where('delivery_order_id', $this->id)->whereNotIn('id', array_keys($items))->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($items as $flag => $item) {
            $item_data = [
                'order_id' => $item['order_id'],
                'order_item_id' => $item['item_id'],
                'title' => $item['title'],
                'quantity' => $item['quantity'],
            ];

            $order_item = OrderItem::find($item['order_id']);
            // 判断是否超出库存数量
            if ($order_item->sku->stock < $item['quantity']) {
                throw new \Exception("[{$order_item->title}]库存不足");
            }

            $item = DeliveryOrderItem::find($flag);

            if (!$item) {
                $item_data['delivery_order_id'] = $this->id;
                $item = DeliveryOrderItem::create($item_data);
            }else {
                $item->update($item_data);
            }

            $order_item = $item->orderItem;


            // 判断是否超出订单Item的数量
            if (0 > $order_item->pending_delivery_quantity) {
                throw new \Exception("[{$order_item->title}]出货数量不可超出待发货数量");
            }
        }

        return $this;
    }

    public function items()
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function express()
    {
        return $this->belongsTo(Express::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDeliveryMethodNameAttribute()
    {
        return isset(self::$delivery_methods[$this->delivery_method]) ? self::$delivery_methods[$this->delivery_method] : '';
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getIsCollectedNameAttribute()
    {
        return 1 == $this->is_collected ? '是' : '否';
    }

    public function getTotalAmountAttribute()
    {
        $total_amount = 0;
        foreach ($this->items as $item) {
            $total_amount += $item->quantity * $item->orderItem->price;
        }

        return $total_amount;
    }
}