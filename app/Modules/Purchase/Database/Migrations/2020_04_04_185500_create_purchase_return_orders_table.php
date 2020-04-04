<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_return_orders', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('purchase_order_id')->default(0)->comment('采购订单ID');
			$table->string('code')->default('')->comment('退货单编号');
			$table->tinyInteger('method')->default(0)->comment('退货方式，1为换货，2为退货');
			$table->text('reason')->default('')->comment('退货原因');
			$table->tinyInteger('status')->default(0)->comment('退货单状态，1为已通过，2为已出库，3为已完成，4为已取消');
			$table->integer('user_id')->default(0)->comment('创建人ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->timestamp('deleted_at')->nullable()->comment('删除时间');

			$table->index('order_id');
			$table->index('code');
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
		Schema::dropIfExists('purchase_return_orders');
	}
}
