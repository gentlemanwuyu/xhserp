<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/21
 * Time: 11:18
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Index\Models\User;

class PurchaseOrderLog extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    static $actions = [
        1 => '同意',
        2 => '驳回',
        3 => '取消',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionNameAttribute()
    {
        return isset(self::$actions[$this->action]) ? self::$actions[$this->action] : '';
    }
}