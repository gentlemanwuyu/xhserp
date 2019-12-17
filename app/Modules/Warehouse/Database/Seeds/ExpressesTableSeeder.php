<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/17
 * Time: 12:43
 */

namespace App\Modules\Warehouse\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Warehouse\Models\Express;

class ExpressesTableSeeder extends Seeder
{
    public function run()
    {
        Express::create(['name' => '顺丰', 'user_id' => 2,]);
        Express::create(['name' => '德邦', 'user_id' => 4,]);
        Express::create(['name' => '速尔', 'user_id' => 3,]);
        Express::create(['name' => '跨越', 'user_id' => 6,]);
    }
}