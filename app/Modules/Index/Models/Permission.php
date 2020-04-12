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

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * 递归地查找所有的父级ID
     *
     * @return array
     */
    public function getParentIdsAttribute()
    {
        $parent_ids = [$this->parent_id];
        $parent = $this->parent;
        if ($parent) {
            $parent_ids = array_merge($parent_ids, $parent->parent_ids);
        }

        return array_filter(array_unique($parent_ids));
    }
}