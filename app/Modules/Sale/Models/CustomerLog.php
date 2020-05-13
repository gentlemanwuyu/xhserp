<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/5/13
 * Time: 14:17
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Index\Models\User;

class CustomerLog extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    const ADD = 1; // 添加
    const EDIT = 2; // 编辑
    const RELEASE = 3; // 释放
    static $actions = [
        self::ADD => '添加',
        self::EDIT => '编辑',
        self::RELEASE => '释放',
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