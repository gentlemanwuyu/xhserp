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
use App\Modules\Goods\Models\Goods;
use App\Modules\Goods\Models\GoodsSku;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function sku()
    {
        return $this->belongsTo(GoodsSku::class, 'sku_id');
    }

    /**
     * 关联发货Item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveryItems()
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    /**
     * 已付款数量
     *
     * @return int
     */
    public function getPaidQuantityAttribute()
    {
        $quantity = 0;

        foreach ($this->deliveryItems as $delivery_item) {
            if (1 == $delivery_item->is_paid) {
                $quantity = $quantity + $delivery_item->quantity;
            }
        }

        return $quantity;
    }
}