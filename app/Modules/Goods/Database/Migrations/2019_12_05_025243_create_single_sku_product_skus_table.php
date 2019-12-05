<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSingleSkuProductSkusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('single_sku_product_skus', function (Blueprint $table) {
			$table->integer('goods_sku_id')->default(0)->comment('商品skuID');
			$table->integer('product_sku_id')->default(0)->comment('产品skuID');

			$table->index('goods_sku_id');
			$table->index('product_sku_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('single_sku_product_skus');
	}
}
