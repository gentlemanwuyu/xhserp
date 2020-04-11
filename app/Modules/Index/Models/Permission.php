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

    public static function tree($parent_id = 0)
    {
        $tree = [];
        $children = self::where('parent_id', $parent_id)->get();
        if ($children) {
            foreach ($children as $child) {
                $item = [
                    'id' => $child->id,
                    'name' => $child->name,
                    'display_name' => $child->display_name,
                    'route' => $child->route,
                    'parent_id' => $child->parent_id,
                ];
                $grand_son = self::tree($child->id);
                if ($grand_son) {
                    $item['children'] = $grand_son;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }
}