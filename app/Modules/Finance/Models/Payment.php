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
use App\Modules\Index\Models\User;
use App\Modules\Purchase\Models\Supplier;

class Payment extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    static $methods = [
        1 => '现金',
        2 => '汇款',
        3 => '支票/汇票',
    ];

    public function items()
    {
        return $this->hasMany(PaymentItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}