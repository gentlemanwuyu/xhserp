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
use App\Modules\Sale\Models\PaymentMethodApplication;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        $customer = Customer::create([
            'name' => '博诚',
            'code' => 'CUSJX001',
            'company' => '无锡博诚',
            'intro' => '',
            'phone' => '',
            'fax' => '',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => '30',
            'state_id' => '810',
            'city_id' => '823',
            'county_id' => '0',
            'street_address' => '博诚公司',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '丁晖', 'position' => '老板', 'phone' => '13626233563']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '毕良山', 'position' => '合伙人', 'phone' => '13395165352']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '宋俊培', 'position' => '合伙人', 'phone' => '15961531625']);

        $customer = Customer::create([
            'name' => '太仓万盛',
            'code' => 'CUSJX002',
            'company' => '太仓万盛',
            'intro' => '',
            'phone' => '',
            'fax' => '',
            'tax' => \Tax::NONE,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::CREDIT,
            'credit' => 10000,
            'state_id' => '810',
            'city_id' => '849',
            'county_id' => '857',
            'street_address' => '',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '小饶', 'position' => '老板', 'phone' => '15206226661']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '老饶', 'position' => '老板', 'phone' => '13814573589']);

        $customer = Customer::create([
            'name' => '东莞杭兴',
            'code' => 'CUSJX003',
            'company' => '东莞市杭兴机械有限公司',
            'intro' => '',
            'phone' => '',
            'fax' => '',
            'tax' => \Tax::NONE,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::CASH,
            'state_id' => '1935',
            'city_id' => '2060',
            'street_address' => '长安镇上沙沙湾街42号',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '王成渠', 'position' => '老板', 'phone' => '13711964532']);

        $customer = Customer::create([
            'name' => '圆康机电',
            'code' => 'CUSJX004',
            'company' => '圆康机电',
            'intro' => '',
            'phone' => '',
            'fax' => '',
            'tax' => \Tax::NONE,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::CASH,
            'state_id' => '810',
            'city_id' => '849',
            'county_id' => '857',
            'street_address' => '',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '凌先生', 'position' => '老板', 'phone' => '15950922802']);
        PaymentMethodApplication::create([
            'customer_id' => $customer->id,
            'payment_method' => \PaymentMethod::CREDIT,
            'credit' => 20000,
            'reason' => '系统自动生成',
            'status' => PaymentMethodApplication::PENDING_REVIEW,
        ]);

        $customer = Customer::create([
            'name' => '深圳景旺',
            'code' => 'CUSZD001',
            'company' => '深圳市景旺电子股份有限公司',
            'intro' => '',
            'phone' => '0755-27697333',
            'fax' => '',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => '90',
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1963',
            'street_address' => '西乡街道铁岗水库路166号(桃花源科技创业中心侧)',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '王先生', 'position' => '采购总监', 'phone' => '13800138000']);

        $customer = Customer::create([
            'name' => '深圳崇达',
            'code' => 'CUSZD002',
            'company' => '崇达技术股份有限公司',
            'intro' => '',
            'phone' => '0755-26068047',
            'fax' => '0755-26068047',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => '90',
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1963',
            'street_address' => '新桥街道新玉路横岗下大街1号',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '邓先生', 'position' => '采购副总监', 'phone' => '13800138000']);

        $customer = Customer::create([
            'name' => '胜宏科技',
            'code' => 'CUSZD003',
            'company' => '胜宏科技（惠州）股份有限',
            'intro' => '',
            'phone' => '0752-3723668',
            'fax' => '',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::CASH,
            'state_id' => '1935',
            'city_id' => '2019',
            'county_id' => '2021',
            'street_address' => '淡水镇行诚科技园',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '特朗普', 'position' => '总统', 'phone' => '13800138000']);

        $customer = Customer::create([
            'name' => '深南电路',
            'code' => 'CUSZD004',
            'company' => '深南电路股份有限公司',
            'intro' => '',
            'phone' => '0755-89308100',
            'fax' => '0755-89300000',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::CASH,
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1964',
            'street_address' => '坪地街道盐龙大道1639号',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '李成', 'position' => '采购', 'phone' => '13517301618']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '陶工', 'position' => '工程', 'phone' => '15818730628']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '罗工', 'position' => '工程部经理', 'phone' => '13603000327']);
        PaymentMethodApplication::create([
            'customer_id' => $customer->id,
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => 60,
            'reason' => '系统自动生成',
            'status' => PaymentMethodApplication::PENDING_REVIEW,
        ]);

        $customer = Customer::create([
            'name' => '天华机器',
            'code' => 'CUSSB001',
            'company' => '深圳天华机器设备有限公司',
            'intro' => '',
            'phone' => '0755-84825596',
            'fax' => '',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => '30',
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1967',
            'street_address' => '坑梓街道龙田社区同富裕工业区25号',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '曾祥秒', 'position' => '老板', 'phone' => '18820276028']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '纽教军', 'position' => '老板', 'phone' => '13502858950']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '徐晓芳', 'position' => '采购', 'phone' => '13590202042']);

        $customer = Customer::create([
            'name' => '华兴四海',
            'code' => 'CUSSB002',
            'company' => '深圳市华兴四海机械设备有限公司',
            'intro' => '',
            'phone' => '0755-28332910',
            'fax' => '0755-28332913',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => '30',
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1964',
            'street_address' => '坪地街道办坪东富地岗工业区A座',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '刘雄', 'position' => '总经理', 'phone' => '13823722397']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '聂金城', 'position' => '采购经理', 'phone' => '18818683356']);

        $customer = Customer::create([
            'name' => '捷佳伟创',
            'code' => 'CUSSB003',
            'company' => '深圳市捷佳伟创新能源装备股份有限公司',
            'intro' => '',
            'phone' => '0755-81449696',
            'fax' => '0755-81449658',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::CASH,
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1967',
            'street_address' => '龙田街道竹坑社区金牛东路62号',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '左国军', 'position' => '总经理', 'phone' => '13823135120']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '陈耀', 'position' => '副总', 'phone' => '15189719588']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '沈靖松', 'position' => '采购经理', 'phone' => '13914349362']);
        PaymentMethodApplication::create([
            'customer_id' => $customer->id,
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => 60,
            'reason' => '系统自动生成',
            'status' => PaymentMethodApplication::PENDING_REVIEW,
        ]);

        $customer = Customer::create([
            'name' => '昆山东威',
            'code' => 'CUSSB004',
            'company' => '昆山东威科技股份有限公司',
            'intro' => '',
            'phone' => '0512-57639780',
            'fax' => '0512-57716758',
            'tax' => \Tax::SEVENTEEN,
            'currency_code' => 'CNY',
            'payment_method' => \PaymentMethod::MONTHLY,
            'monthly_day' => 60,
            'state_id' => '810',
            'city_id' => '849',
            'county_id' => '857',
            'street_address' => '开发区洞庭湖路9号',
            'manager_id' => rand(2, 31),
        ]);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '刘建波', 'position' => '总经理', 'phone' => '13382510777']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '谢玉龙', 'position' => '副总', 'phone' => '13382510555']);
        CustomerContact::create(['customer_id' => $customer->id, 'name' => '肖治国', 'position' => '总经理', 'phone' => '13823756489']);
    }
}