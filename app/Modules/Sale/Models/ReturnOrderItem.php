<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/2/15
 * Time: 23:46
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnOrderItem extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}