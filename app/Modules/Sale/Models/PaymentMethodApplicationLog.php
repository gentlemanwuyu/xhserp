<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/23
 * Time: 15:20
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodApplicationLog extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
}