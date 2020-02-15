<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/2/15
 * Time: 23:46
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Index\Models\User;
use App\Traits\CodeTrait;

class ReturnOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhsro';

    static $statuses = [
        1 => '待审核',
        2 => '已驳回',
        3 => '已通过',
        4 => '已完成',
        5 => '已取消',
    ];

    public function items()
    {
        return $this->hasMany(ReturnOrderItem::class);
    }

    public function getStatusNameAttribute()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}