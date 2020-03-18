<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/3/8
 * Time: 12:23
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderItemExchange extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
}