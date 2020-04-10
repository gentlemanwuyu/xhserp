<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/10
 * Time: 12:58
 */

namespace App\Modules\Index\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    static $types = [
        1 => '菜单',
        2 => '按钮',
    ];
}