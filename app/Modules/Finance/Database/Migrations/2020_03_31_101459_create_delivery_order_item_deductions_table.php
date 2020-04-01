<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderItemDeductionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_order_item_deductions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('delivery_order_item_id')->default(0)->comment('出货单ItemID');
			$table->integer('collection_id')->default(0)->comment('收款单ID');
			$table->integer('return_order_id')->default(0)->comment('退货单ID');
			$table->decimal('amount', 8, 2)->default(0.00)->comment('抵扣金额');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('delivery_order_item_id');
			$table->index('collection_id');
			$table->index('return_order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('delivery_order_item_deductions');
	}
}
