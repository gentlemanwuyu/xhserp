<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/14
 * Time: 17:10
 */

namespace App\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Index\Models\User;
use App\Modules\Purchase\Models\PurchaseOrderItem;

class SkuEntry extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exchanges()
    {
        return $this->hasMany(SkuEntryExchange::class, 'entry_id');
    }

    /**
     * 分配数量(换货优先)
     *
     * @return $this
     */
    public function assignQuantity()
    {
        $pending_exchange_items = $this->purchaseOrderItem->pendingExchangeItems;
        $remained_quantity = $this->quantity;
        // 先抵扣换货数量
        while($pending_exchange_item = $pending_exchange_items->shift()) {
            // 待换货数量
            $pending_exchange_quantity = $pending_exchange_item->quantity - $pending_exchange_item->entried_quantity;
            if ($pending_exchange_quantity > $remained_quantity) {
                SkuEntryExchange::create([
                    'entry_id' => $this->id,
                    'purchase_return_order_item_id' => $pending_exchange_item->id,
                    'quantity' => $remained_quantity,
                ]);
                $remained_quantity = 0;
            }else {
                $pending_exchange_item->entried_quantity += $pending_exchange_quantity;
                SkuEntryExchange::create([
                    'entry_id' => $this->id,
                    'purchase_return_order_item_id' => $pending_exchange_item->id,
                    'quantity' => $pending_exchange_quantity,
                ]);
                $remained_quantity -= $pending_exchange_quantity;
            }
            // 如果还有数量剩下，则继续循环抵扣
            if (0 >= $remained_quantity) {
                break;
            }
        }
        // 如果抵扣完还有数量剩下，那么这个就是真实的出货数量
        if (0 < $remained_quantity) {
            $this->real_quantity = $remained_quantity;
            $this->save();
        }

        return $this;
    }

    public function getIsPaidNameAttribute()
    {
        return YES == $this->is_paid ? '是' : '否';
    }
}