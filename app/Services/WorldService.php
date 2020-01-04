<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/7
 * Time: 13:10
 */

namespace App\Services;

use Wuyu\World\Facades\World;
use Illuminate\Support\Facades\Redis;

class WorldService
{

    public function __construct()
    {

    }

    public static function chineseTree($parent_id = 0)
    {
        if ($tree = Redis::get('xhserp:world:chinese_tree_' . $parent_id)) {
            $tree = json_decode($tree, true);
            return $tree;
        }

        $tree = World::chineseTree($parent_id);
        Redis::setnx('xhserp:world:chinese_tree_' . $parent_id, json_encode($tree));

        return $tree;
    }

    public static function countries()
    {
        $countries = Redis::get('xhserp:world:countries');
        if ($countries) {
            return json_decode($countries, true);
        }

        $countries = World::countries();
        Redis::setnx('xhserp:world:countries', json_encode($countries));

        return $countries;
    }
}