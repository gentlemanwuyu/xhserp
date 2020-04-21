<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/8
 * Time: 11:20
 */

namespace App\Modules\Sale\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\CustomerContact;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        $customer = Customer::create([
            'name' => '博诚',
            'code' => 'xhsjxs001',
            'company' => '无锡博诚',
            'intro' => '',
            'phone' => '',
            'fax' => '',
            'tax' => 3,
            'currency_code' => 'CNY',
            'payment_method' => 3,
            'monthly_day' => '30',
            'state_id' => '810',
            'city_id' => '823',
            'county_id' => '0',
            'street_address' => '博诚公司',
            'manager_id' => '3',
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '丁晖', 'position' => '老板', 'phone' => '13626233563']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '毕良山', 'position' => '合伙人', 'phone' => '13395165352']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '宋俊培', 'position' => '合伙人', 'phone' => '15961531625']);

        $customer = Customer::create([
            'name' => '太仓万盛',
            'code' => 'xhsjxs002',
            'company' => '太仓万盛',
            'intro' => '',
            'phone' => '',
            'fax' => '',
            'tax' => 1,
            'currency_code' => 'CNY',
            'payment_method' => 2,
            'credit' => 10000,
            'state_id' => '810',
            'city_id' => '849',
            'county_id' => '857',
            'street_address' => '',
            'manager_id' => '5',
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '小饶', 'position' => '老板', 'phone' => '15206226661']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '老饶', 'position' => '老板', 'phone' => '13814573589']);

        $customer = Customer::create([
            'name' => '深圳景旺',
            'code' => 'xhszd001',
            'company' => '深圳市景旺电子股份有限公司',
            'intro' => '',
            'phone' => '0755-27697333',
            'fax' => '',
            'tax' => 3,
            'currency_code' => 'CNY',
            'payment_method' => 3,
            'monthly_day' => '90',
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1963',
            'street_address' => '西乡街道铁岗水库路166号(桃花源科技创业中心侧)',
            'manager_id' => '5',
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '王先生', 'position' => '采购总监', 'phone' => '13800138000']);

        $customer = Customer::create([
            'name' => '深圳崇达',
            'code' => 'xhszd002',
            'company' => '崇达技术股份有限公司',
            'intro' => '',
            'phone' => '0755-26068047',
            'fax' => '0755-26068047',
            'tax' => 3,
            'currency_code' => 'CNY',
            'payment_method' => 3,
            'monthly_day' => '90',
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1963',
            'street_address' => '新桥街道新玉路横岗下大街1号',
            'manager_id' => '6',
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '邓先生', 'position' => '采购副总监', 'phone' => '13800138000']);

        $customer = Customer::create([
            'name' => '东莞杭兴',
            'code' => 'xhsjx003',
            'company' => '东莞市杭兴机械有限公司',
            'intro' => '',
            'phone' => '',
            'fax' => '',
            'tax' => 1,
            'currency_code' => 'CNY',
            'payment_method' => 1,
            'state_id' => '1935',
            'city_id' => '2060',
            'street_address' => '长安镇上沙沙湾街42号',
            'manager_id' => '10',
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '王成渠', 'position' => '老板', 'phone' => '13711964532']);
    }
}