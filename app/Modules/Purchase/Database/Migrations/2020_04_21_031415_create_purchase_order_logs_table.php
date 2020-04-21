<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderLogsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_order_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('purchase_order_id')->default(0)->comment('采购订单ID');
			$table->tinyInteger('action')->default(0)->comment('操作，1为通过，2为驳回，3为取消');
			$table->text('content')->default('')->comment('内容');
			$table->integer('user_id')->default(0)->comment('操作人ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('purchase_order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('purchase_order_logs');
	}
}
