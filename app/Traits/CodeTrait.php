<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/2/8
 * Time: 13:31
 */

namespace App\Traits;

use Carbon\Carbon;

Trait CodeTrait
{
    /**
     * 订单编号生成器
     *
     * @return string
     */
    public static function codeGenerator($date = null)
    {
        $carbon = $date ? Carbon::parse($date) : Carbon::now();

        $todayNumber = static::where('created_at', '>=', $carbon->toDateString() . ' 00:00:00')
            ->where('created_at', '<=', $carbon->toDateString() . ' 23:59:59')
            ->count();

        do {
            $todayNumber++;
            $number = sprintf('%04s', $todayNumber);
            $code = static::CODE_PREFIX . $carbon->format('Ymd') . $number;
            $codeExists = static::where('code', $code)->first();
            if ($codeExists) {
                $code = '';
            }
        }while(!$code);

        return $code;
    }
}