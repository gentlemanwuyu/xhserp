<?php
namespace App\Modules\Index\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class IndexDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		 $this->call(UsersTableSeeder::class);
		 $this->call(RolesTableSeeder::class);
	}

}
