<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/5/26
 * Time: 15:34
 */

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Modules\Index\Models\Permission;

class EntrustService
{
    public function __construct()
    {

    }

    /**
     * 权限树，优先取自缓存
     *
     * @param int $parent_id
     * @return array|mixed
     */
    public static function permissionTree($parent_id = 0)
    {
        if ($tree = Cache::get('entrust:permission_tree_' . $parent_id)) {
            $tree = json_decode($tree, true);
            return $tree;
        }

        $tree = Permission::tree($parent_id);

        Cache::forever('entrust:permission_tree_' . $parent_id, json_encode($tree));

        return $tree;
    }
}