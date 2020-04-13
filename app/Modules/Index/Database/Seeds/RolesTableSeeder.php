<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/13
 * Time: 15:54
 */

namespace App\Modules\Index\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Index\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $role = Role::create(['name' => 'general_manager', 'display_name' => '总经理']);
        $role->giveAllPermissions();

        $role = Role::create(['name' => 'chief_sales_officer', 'display_name' => '销售总监']);
        $role->syncPermissions([
            'sale_management',
            'customer_management',
            'customer_detail',
            'add_customer',
            'edit_customer',
            'delete_customer',
            'payment_method_application',
            'edit_payment_method_application',
            'review_payment_method_application',
            'delete_payment_method_application',
            'order_management',
            'order_detail',
            'add_order',
            'edit_order',
            'delete_order',
            'order_delivery',
            'order_return',
            'delivery_order_management',
            'delivery_order_detail',
            'edit_delivery_order',
            'delete_delivery_order',
            'return_order_management',
            'return_order_detail',
            'edit_return_order',
            'review_return_order',
            'delete_return_order',
        ]);

        $role = Role::create(['name' => 'salesman', 'display_name' => '业务员']);
        $role->syncPermissions([
            'sale_management',
            'customer_management',
            'customer_detail',
            'add_customer',
            'edit_customer',
            'payment_method_application',
            'edit_payment_method_application',
            'delete_payment_method_application',
            'order_management',
            'order_detail',
            'add_order',
            'edit_order',
            'order_delivery',
            'order_return',
            'delivery_order_management',
            'delivery_order_detail',
            'edit_delivery_order',
            'delete_delivery_order',
            'return_order_management',
            'return_order_detail',
            'edit_return_order',
            'delete_return_order',
        ]);

        $role = Role::create(['name' => 'treasurer', 'display_name' => '财务主管']);
        $role->syncPermissions([
            'category_management',
            'product_category',
            'goods_category',
            'product_management',
            'goods_management',
            'goods_list',
            'goods_detail',
            'warehouse_management',
            'stockout_management',
            'entry_management',
            'egress_management',
            'egress_detail',
            'sale_return_management',
            'purchase_return_management',
            'express_management',
            'purchase_management',
            'supplier_management',
            'purchase_order_management',
            'purchase_order_detail',
            'purchase_return_order_management',
            'purchase_return_order_detail',
            'sale_management',
            'customer_management',
            'customer_detail',
            'payment_method_application',
            'order_management',
            'order_detail',
            'delivery_order_management',
            'delivery_order_detail',
            'return_order_management',
            'return_order_detail',
            'finance_management',
            'collection_management',
            'add_collection',
            'pending_collection',
            'deduct_pending_collection',
            'payment_management',
            'add_payment',
            'pending_payment',
            'deduct_pending_payment',
            'account_management',
            'add_account',
            'edit_account',
            'delete_account',
        ]);
    }
}