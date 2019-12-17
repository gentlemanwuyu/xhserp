<?php
namespace App\Modules\Sale\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SaleDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		 $this->call(CustomersTableSeeder::class);
		 $this->call(OrdersTableSeeder::class);
		 $this->call(DeliveryOrdersTableSeeder::class);
	}

}
