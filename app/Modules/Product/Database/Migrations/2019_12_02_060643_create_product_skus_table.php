<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_skus', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('product_id')->default(0)->comment('产品ID');
			$table->string('code')->default('')->comment('sku编号');
			$table->decimal('weight', 8, 2)->default(0.00)->comment('重量');
			$table->decimal('cost_price', 8, 2)->default(0.00)->comment('成本价');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('product_id');
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
		Schema::dropIfExists('product_skus');
	}
}
