<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/24
 * Time: 10:11
 */

namespace App\Modules\Index\Database\Seeds;

use App\Modules\Index\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        module_factory('Index', User::class, 30)->create();
    }
}