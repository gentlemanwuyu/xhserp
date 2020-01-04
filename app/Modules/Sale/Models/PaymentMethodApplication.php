<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/1/4
 * Time: 12:45
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Index\Models\User;

class PaymentMethodApplication extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    static $statuses = [
        1 => '待审核',
        2 => '已驳回',
        3 => '已通过',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function getPaymentMethodNameAttribute()
    {
        return isset(Customer::$payment_methods[$this->payment_method]) ? Customer::$payment_methods[$this->payment_method] : '';
    }
}