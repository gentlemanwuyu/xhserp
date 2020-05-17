<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsSkusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('goods_skus', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('goods_id')->default(0)->comment('商品ID');
			$table->string('code')->default('')->comment('sku编号');
			$table->string('size')->default('')->comment('规格');
			$table->string('model')->default('')->comment('型号');
			$table->decimal('lowest_price', 8, 2)->default(0.00)->comment('最低售价');
			$table->decimal('msrp', 8, 2)->default(0.00)->comment('建议零售价');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('goods_id');
			$table->index('code');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('goods_skus');
	}
}
