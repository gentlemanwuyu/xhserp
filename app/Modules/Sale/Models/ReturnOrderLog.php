<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/2/22
 * Time: 19:54
 */

namespace App\Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Index\Models\User;

class ReturnOrderLog extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // 操作
    const AGREE = 1;
    const REJECT = 2;
    const HANDLE = 3;
    static $actions = [
        self::AGREE     => '同意',
        self::REJECT    => '驳回',
        self::HANDLE    => '处理',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}