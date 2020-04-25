<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/26
 * Time: 15:28
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;

class Collection extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    // 收款方式
    static $methods = [
        \Payment::CASH          => '现金',
        \Payment::REMITTANCE    => '汇款',
        \Payment::CHECK         => '支票/汇票',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deductions()
    {
        return $this->hasMany(DeliveryOrderItemDeduction::class);
    }

    public function getMethodNameAttribute()
    {
        return isset(self::$methods[$this->method]) ? self::$methods[$this->method] : '';
    }
}