<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/30
 * Time: 20:00
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Currency;
use App\Modules\Index\Models\User;
use App\Modules\Purchase\Models\Supplier;

class Payment extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    // 付款方式
    static $methods = [
        \Payment::CASH          => '现金',
        \Payment::REMITTANCE    => '汇款',
        \Payment::CHECK         => '支票/汇票',
    ];

    public function deductions()
    {
        return $this->hasMany(SkuEntryDeduction::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function getMethodNameAttribute()
    {
        return isset(self::$methods[$this->method]) ? self::$methods[$this->method] : '';
    }
}