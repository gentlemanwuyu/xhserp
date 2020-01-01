<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/7
 * Time: 13:32
 */

namespace App\Modules\Purchase\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Purchase\Models\Supplier;
use App\Modules\Purchase\Models\SupplierContact;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        $supplier = Supplier::create([
            'name' => '塑霸宏图',
            'code' => 'xhsgys001',
            'company' => '深圳市塑霸宏图塑胶制品有限公司',
            'intro' => '',
            'phone' => '0755-89641829',
            'fax' => '0755-89643686',
            'tax' => 1,
            'payment_method' => 1,
            'state_id' => '1935',
            'city_id' => '1959',
            'county_id' => '1964',
            'street_address' => '龙岗街道龙东盈科利工业区',
        ]);
        SupplierContact::create(['supplier_id' => $supplier->id, 'name' => '聂小平', 'position' => '老板', 'phone' => '13924585198']);
        SupplierContact::create(['supplier_id' => $supplier->id, 'name' => '聂广伦', 'position' => '老板', 'phone' => '13924585268']);
        SupplierContact::create(['supplier_id' => $supplier->id, 'name' => '余小姐', 'position' => '老板', 'phone' => '13530130472']);
        SupplierContact::create(['supplier_id' => $supplier->id, 'name' => '李小姐', 'position' => '老板', 'phone' => '13501576968']);

        $supplier = Supplier::create([
            'name' => '东莞扬升',
            'code' => 'xhsgys002',
            'company' => '东莞扬升玻纤制品有限公司',
            'intro' => '',
            'phone' => '0769-85131315',
            'fax' => '0769-85131317',
            'tax' => 3,
            'payment_method' => 3,
            'monthly_day' => 30,
            'state_id' => '1935',
            'city_id' => '2060',
            'county_id' => '0',
            'street_address' => '虎门镇白沙三村工业区福升路',
        ]);
        SupplierContact::create(['supplier_id' => $supplier->id, 'name' => '秦先生', 'position' => '', 'phone' => '13316686652']);
    }
}