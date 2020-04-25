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

    // 出货方式
    const BY_SELF   = 1;
    const SEND      = 2;
    const EXPRESS   = 3;
    static $delivery_methods = [
        self::BY_SELF   => '客户自取',
        self::SEND      => '送货',
        self::EXPRESS   => '快递物流',
    ];

    // 出货单状态
    const PENDING_REVIEW    = 1;
    const PENDING_DELIVERY  = 2;
    const FINISHED          = 3;
    static $statuses = [
        self::PENDING_REVIEW    => '待审核',
        self::PENDING_DELIVERY  => '待出货',
        self::FINISHED          => '已完成',
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

            $order_item = OrderItem::find($item['item_id']);
            // 判断是否超出库存数量
            if ($order_item->sku->stock < $item['quantity']) {
                throw new \Exception("[{$order_item->title}]库存不足");
            }

            $delivery_order_item = DeliveryOrderItem::find($flag);

            // 判断是否超出订单Item的待发货数量
            $pending_delivery_quantity = $order_item->pending_delivery_quantity;
            if ($delivery_order_item) {
                $pending_delivery_quantity += $delivery_order_item->quantity;
            }
            if ($item['quantity'] > $pending_delivery_quantity) {
                throw new \Exception("[{$order_item->title}]出货数量[{$item['quantity']}]不可超出待出货数量[{$pending_delivery_quantity}]");
            }

            if (!$delivery_order_item) {
                $item_data['delivery_order_id'] = $this->id;
                $delivery_order_item = DeliveryOrderItem::create($item_data);
            }else {
                $delivery_order_item->update($item_data);
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