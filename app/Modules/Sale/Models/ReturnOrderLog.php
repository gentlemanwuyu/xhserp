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

    static $actions = [
        1 => '同意',
        2 => '驳回',
        3 => '处理',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}