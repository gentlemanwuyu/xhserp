<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/24
 * Time: 16:43
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Index\Models\User;

class OrderLog extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // 操作
    const AGREE = 1;
    const REJECT = 2;
    const CANCEL = 3;
    static $actions = [
        self::AGREE     => '同意',
        self::REJECT    => '驳回',
        self::CANCEL    => '取消',
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