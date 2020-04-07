<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/5
 * Time: 3:00
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CodeTrait;
use App\Modules\Index\Models\User;

class PurchaseReturnOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhspro';

    static $statuses = [
        1 => '已通过',
        2 => '已出库',
        3 => '已完成',
        4 => '已取消',
    ];

    static $methods = [
        1 => '换货',
        2 => '退货',
    ];

    static $delivery_methods = [
        1 => '供应商自取',
        2 => '送货',
        3 => '快递物流',
    ];

    public function syncItems($items)
    {
        if (!$items || !is_array($items)) {
            return false;
        }

        // 将不在请求中的item删除
        PurchaseReturnOrderItem::where('purchase_return_order_id', $this->id)->whereNotIn('id', array_keys($items))->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($items as $flag => $item) {
            $item_data = array_only($item, [
                'purchase_order_item_id',
                'quantity',
                'egress_quantity',
            ]);

            $item = PurchaseReturnOrderItem::find($flag);

            if (!$item) {
                // 判断是否超出已出货数量
                $purchase_order_item = PurchaseOrderItem::find($item_data['purchase_order_item_id']);
                if ($purchase_order_item->returnable_quantity < $item_data['quantity']) {
                    throw new \Exception("[{$purchase_order_item->title}]退货数量[{$item_data['quantity']}]不可超出可退数量[{$purchase_order_item->returnable_quantity}]");
                }

                $item_data['purchase_return_order_id'] = $this->id;
                $item = PurchaseReturnOrderItem::create($item_data);
            }else {
                // 判断是否超出已出货数量
                if (!empty($item_data['quantity'])) {
                    $purchase_order_item = $item->purchaseOrderItem;
                    $returnable_quantity = $purchase_order_item->returnable_quantity + $item->quantity;
                    if ($item_data['quantity'] > $returnable_quantity) {
                        throw new \Exception("[{$purchase_order_item->title}]退货数量[{$item_data['quantity']}]不可超出可退数量[{$returnable_quantity}]");
                    }
                }
                $item->update($item_data);
            }
        }

        return $this;
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnOrderItem::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getMethodNameAttribute()
    {
        return isset(self::$methods[$this->method]) ? self::$methods[$this->method] : '';
    }
}