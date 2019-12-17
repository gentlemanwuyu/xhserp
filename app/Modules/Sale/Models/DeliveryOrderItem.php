<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/17
 * Time: 19:49
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrderItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
}