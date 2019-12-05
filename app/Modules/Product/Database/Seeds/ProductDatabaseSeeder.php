<?php
namespace App\Modules\Product\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ProductDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		 $this->call(ProductsTableSeeder::class);
		$this->call(\App\Modules\Goods\Database\Seeds\GoodsTableSeeder::class);
	}

}
