<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderItemBacksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_order_item_backs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('delivery_order_item_id')->default(0)->comment('出货单Item ID');
			$table->integer('return_order_item_id')->default(0)->comment('退货单Item ID');
			$table->integer('quantity')->default(0)->comment('退回数量');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('delivery_order_item_id');
			$table->index('return_order_item_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('delivery_order_item_backs');
	}
}
