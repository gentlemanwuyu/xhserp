<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_order_items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('delivery_order_id')->default(0)->comment('出货单ID');
			$table->integer('order_id')->default(0)->comment('订单ID');
			$table->integer('order_item_id')->default(0)->comment('订单ItemID');
			$table->string('title')->default('')->comment('标题');
			$table->integer('quantity')->default(0)->comment('数量');
			$table->integer('real_quantity')->default(0)->comment('真实数量，出货数量');
			$table->enum('is_paid', [0, 1])->default(0)->comment('是否已付款, 1为已付款');
			$table->timestamp('delivered_at')->nullable()->default(null)->comment('交付时间');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('delivery_order_id');
			$table->index('order_id');
			$table->index('order_item_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('delivery_order_items');
	}
}
