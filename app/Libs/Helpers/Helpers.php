<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/24
 * Time: 10:19
 */

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Cache;
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
        if ($sys_configs = Cache::get('system:configs')) {
            $sys_configs = json_decode($sys_configs, true);
            return $sys_configs;
        }

        $sys_configs = array_column(Config::all(['key', 'value'])->toArray(), 'value', 'key');
        Cache::put('system:configs', json_encode($sys_configs), 14400);

        return $sys_configs;
    }
}

if (! function_exists('flush_sys_configs')) {
    /**
     * 刷新系统配置缓存
     *
     * @param $module
     * @return object
     */
    function flush_sys_configs()
    {
        Cache::forget('system:configs');

        $sys_configs = array_column(Config::all(['key', 'value'])->toArray(), 'value', 'key');
        Cache::put('system:configs', json_encode($sys_configs), 14400);

        return true;
    }
}

if (! function_exists('price_format')) {
    /**
     * 价格格式, 保留小数点后两位
     *
     * @param $price
     * @return string
     */
    function price_format($price)
    {
        if (!$price) {
            return $price;
        }

        return number_format($price, 2, '.', '');
    }
}

if (! function_exists('amount_to_cn')) {
    /**
     * 将金额转成中文写法
     *
     * @param $amount
     * @return string
     */
    function amount_to_cn($amount)
    {
        if (0 == $amount) {
            return "零元整";
        }

        if (12 < strlen($amount)) {
            return "不支持万亿及更高金额";
        }

        // 预定义中文转换的数组
        $digital = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        // 预定义单位转换的数组
        $position = array('仟', '佰', '拾', '亿', '仟', '佰', '拾', '万', '仟', '佰', '拾', '元');

        // 将金额的数值字符串拆分成数组
        $amountArr = explode('.', $amount);
        // 将整数位的数值字符串拆分成数组
        $integerArr = str_split($amountArr[0], 1);

        // 将整数部分替换成大写汉字
        $result = '';
        $integerArrLength = count($integerArr);
        $positionLength = count($position);
        for ($i=0; $i<$integerArrLength; $i++) {
            $result = $result . $digital[$integerArr[$i]]. $position[$positionLength - $integerArrLength + $i];
        }

        // 如果存在小数位，继续转换小数位
        if (!empty($amountArr[1])) {
            $decimalArr = str_split($amountArr[1], 1);
            if (isset($decimalArr[0])) {
                $result .= $digital[$decimalArr[0]] . '角';
            }
            if (isset($decimalArr[1])) {
                $result .= $digital[$decimalArr[1]] . '分';
            }
        }else {
            $result .= '整';
        }

        return $result;
    }
}

if (! function_exists('array_piece')) {
    /**
     * 价格格式, 保留小数点后两位
     *
     * @param $price
     * @return string
     */
    function array_piece($array, $piece_len)
    {
        $result = [];
        if ($array && is_array($array)) {
            $arr_len = count($array);
            $offset = 0;
            do {
                $result[] = array_slice($array, $offset, $piece_len);
                $offset += $piece_len;
            }while($offset < $arr_len);
        }

        return $result;
    }
}