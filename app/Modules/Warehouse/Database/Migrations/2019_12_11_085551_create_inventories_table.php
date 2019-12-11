<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventories', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sku_id')->default(0)->comment('SkuID');
			$table->integer('stock')->default(0)->comment('库存数量');
			$table->integer('highest_stock')->default(0)->comment('最高库存');
			$table->integer('lowest_stock')->default(0)->comment('最低库存');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->unique('sku_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('inventories');
	}
}
