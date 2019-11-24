<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/24
 * Time: 10:10
 */

$factory->define(\App\Modules\Index\Models\User::class, function (\Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => $password ?: $password = bcrypt('gentleman'),
        'birthday'       => $faker->date(),
        'gender_id'         => mt_rand(0, 2),
        'remember_token' => str_random(10),
    ];
});