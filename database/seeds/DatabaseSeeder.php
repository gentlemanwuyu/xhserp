<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Sale\Database\Seeds\CustomersTableSeeder;
use App\Modules\Sale\Database\Seeds\OrdersTableSeeder;
use App\Modules\Sale\Database\Seeds\DeliveryOrdersTableSeeder;
use App\Modules\Warehouse\Database\Seeds\ExpressesTableSeeder;
use App\Modules\Category\Database\Seeds\CategoriesTableSeeder;
use App\Modules\Finance\Database\Seeds\AccountsTableSeeder;
use App\Modules\Purchase\Database\Seeds\SuppliersTableSeeder;
use App\Modules\Purchase\Database\Seeds\PurchaseOrdersTableSeeder;
use App\Modules\Index\Database\Seeds\UsersTableSeeder;
use App\Modules\Index\Database\Seeds\RolesTableSeeder;
use App\Modules\Product\Database\Seeds\ProductsTableSeeder;
use App\Modules\Goods\Database\Seeds\GoodsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CategoriesTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(GoodsTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(PurchaseOrdersTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(DeliveryOrdersTableSeeder::class);
        $this->call(ExpressesTableSeeder::class);
    }
}
