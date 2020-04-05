<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuEntriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sku_entries', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sku_id')->default(0)->comment('SkuID');
			$table->integer('purchase_order_item_id')->default(0)->comment('订单itemID');
			$table->integer('quantity')->default(0)->comment('入库数量');
			$table->enum('is_paid', [0, 1])->default(0)->comment('是否已付款, 1为已付款');
			$table->integer('user_id')->default(0)->comment('操作人ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('sku_id');
			$table->index('purchase_order_item_id');
			$table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sku_entries');
	}
}
