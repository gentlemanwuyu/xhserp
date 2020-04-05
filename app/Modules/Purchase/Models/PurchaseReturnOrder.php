<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/5
 * Time: 3:00
 */

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CodeTrait;

class PurchaseReturnOrder extends Model
{
    use SoftDeletes, CodeTrait;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    const CODE_PREFIX = 'xhspro';

    static $statuses = [
        1 => '已通过',
        2 => '已出库',
        3 => '已完成',
        4 => '已取消',
    ];

    static $methods = [
        1 => '换货',
        2 => '退货',
    ];
}