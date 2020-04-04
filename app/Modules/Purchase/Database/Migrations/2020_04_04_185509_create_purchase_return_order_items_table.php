<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnOrderItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_return_order_items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('purchase_return_order_id')->default(0)->comment('退货单ID');
			$table->integer('purchase_order_item_id')->default(0)->comment('订单ItemID');
			$table->integer('quantity')->default(0)->comment('数量');
			$table->integer('egress_quantity')->default(0)->comment('出库数量');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('purchase_return_order_id');
			$table->index('purchase_order_item_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('purchase_return_order_items');
	}
}
