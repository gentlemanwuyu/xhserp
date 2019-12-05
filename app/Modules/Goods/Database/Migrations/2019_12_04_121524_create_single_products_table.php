<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSingleProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('single_products', function (Blueprint $table) {
			$table->integer('goods_id')->default(0)->comment('商品ID');
			$table->integer('product_id')->default(0)->comment('产品ID');

			$table->unique('goods_id');
			$table->index('product_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('single_products');
	}
}
