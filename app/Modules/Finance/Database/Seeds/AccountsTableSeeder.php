<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/25
 * Time: 20:52
 */

namespace App\Modules\Finance\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Finance\Models\Account;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        Account::create([
            'name' => '不含税收款账户1',
            'bank' => '平安银行',
            'branch' => '深圳分行龙岗支行',
            'payee' => '吴冠',
            'account' => '6123456200185001455',
        ]);
        Account::create([
            'name' => '不含税收款账户2',
            'bank' => '中国银行',
            'branch' => '深圳分行龙翔支行',
            'payee' => '委员长',
            'account' => '6123456200654001455',
        ]);
        Account::create([
            'name' => '公司公账',
            'bank' => '中国建设银行',
            'branch' => '深圳分行九州支行',
            'payee' => '成吉思汗',
            'account' => '612352018654001455',
        ]);
        Account::create([
            'name' => '经销部账户',
            'bank' => '中国工商银行',
            'branch' => '深圳分行西丽支行',
            'payee' => '吴老师',
            'account' => '6123520186895125455',
        ]);
    }
}