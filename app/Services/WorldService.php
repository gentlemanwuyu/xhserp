<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/7
 * Time: 13:10
 */

namespace App\Services;

use Wuyu\World\Facades\World;
use Illuminate\Support\Facades\Cache;
use App\Models\Currency;

class WorldService
{

    public function __construct()
    {

    }

    public static function chineseTree($parent_id = 0)
    {
        if ($tree = Cache::get('world:chinese_tree_' . $parent_id)) {
            $tree = json_decode($tree, true);
            return $tree;
        }

        $tree = World::chineseTree($parent_id);
        Cache::forever('world:chinese_tree_' . $parent_id, json_encode($tree));

        return $tree;
    }

    public static function countries()
    {
        $countries = Cache::get('world:countries');
        if ($countries) {
            return json_decode($countries, true);
        }

        $countries = World::countries();
        Cache::forever('world:countries', json_encode($countries));

        return $countries;
    }

    public static function currencies()
    {
        $currencies = Currency::all();

        return array_column(json_decode(json_encode($currencies), true), null, 'code');
    }
}