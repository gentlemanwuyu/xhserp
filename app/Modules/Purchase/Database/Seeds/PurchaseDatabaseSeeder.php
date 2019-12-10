<?php
namespace App\Modules\Purchase\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PurchaseDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		 $this->call(SuppliersTableSeeder::class);
		 $this->call(PurchaseOrdersTableSeeder::class);
	}

}
