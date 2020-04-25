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

    // 付款方式申请状态
    const PENDING_REVIEW = 1;
    const REJECTED = 2;
    const AGREED = 3;
    static $statuses = [
        self::PENDING_REVIEW    => '待审核',
        self::REJECTED          => '已驳回',
        self::AGREED            => '已通过',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(PaymentMethodApplicationLog::class)->orderBy('id', 'desc');
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