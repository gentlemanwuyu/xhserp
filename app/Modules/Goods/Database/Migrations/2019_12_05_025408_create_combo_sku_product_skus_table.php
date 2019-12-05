<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComboSkuProductSkusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('combo_sku_product_skus', function (Blueprint $table) {
			$table->integer('goods_sku_id')->default(0)->comment('商品skuID');
			$table->integer('product_id')->default(0)->comment('产品ID');
			$table->integer('product_sku_id')->default(0)->comment('产品skuID');

			$table->index('goods_sku_id');
			$table->index('product_id');
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
		Schema::dropIfExists('combo_sku_product_skus');
	}
}
