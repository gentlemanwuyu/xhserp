<?php
/**
 * 发货单Item抵扣记录模型
 *
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/3/31
 * Time: 18:18
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Sale\Models\DeliveryOrderItem;

class DeliveryOrderItemDeduction extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function deliveryOrderItem()
    {
        return $this->belongsTo(DeliveryOrderItem::class);
    }
}