<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/24
 * Time: 10:10
 */

$factory->define(\App\Modules\Index\Models\User::class, function (\Faker\Generator $faker) {
    static $password;
    $genders = array_keys(\App\Modules\Index\Models\User::$genders);
    shuffle($genders);
    $statuses = array_keys(\App\Modules\Index\Models\User::$statuses);
    shuffle($statuses);

    return [
        'name'              => $faker->name,
        'email'             => $faker->safeEmail,
        'password'          => $password ?: $password = bcrypt('gentleman'),
        'birthday'          => $faker->date(),
        'gender_id'         => array_shift($genders),
        'remember_token'    => str_random(10),
        'status'            => array_shift($statuses),
    ];
});