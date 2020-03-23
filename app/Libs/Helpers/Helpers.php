<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/24
 * Time: 10:19
 */

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Redis;
use App\Modules\Index\Models\Config;

if (! function_exists('module_path')) {
    /**
     * 获取模块根路径
     *
     * @param $module
     * @return string
     */
    function module_path($module)
    {
        return app_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.studly_case($module);
    }
}

if (! function_exists('module_factory')) {
    /**
     * 获取模块工厂对象
     *
     * @param $module
     * @return object
     */
    function module_factory($module)
    {
        $pathToFactories = module_path($module).DIRECTORY_SEPARATOR.'Database'.DIRECTORY_SEPARATOR.'Factories';
        $factory = EloquentFactory::construct(app(Faker::class), $pathToFactories);

        $arguments = func_get_args();

        if (isset($arguments[2]) && is_string($arguments[2])) {
            return $factory->of($arguments[1], $arguments[2])->times(isset($arguments[3]) ? $arguments[3] : null);
        } elseif (isset($arguments[2])) {
            return $factory->of($arguments[1])->times($arguments[2]);
        }

        return $factory->of($arguments[1]);
    }
}

if (! function_exists('get_sys_configs')) {
    /**
     * 获取系统配置
     *
     * @param $module
     * @return object
     */
    function get_sys_configs()
    {
        if ($sys_configs = Redis::get('erp:system:configs')) {
            $sys_configs = json_decode($sys_configs, true);
            return $sys_configs;
        }

        $sys_configs = array_column(Config::all(['key', 'value'])->toArray(), 'value', 'key');
        Redis::setnx('erp:system:configs', json_encode($sys_configs));

        return $sys_configs;
    }
}