<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('return_orders', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->default(0)->comment('订单ID');
			$table->string('code')->default('')->comment('退货单编号');
			$table->tinyInteger('method')->default(0)->comment('退货方式，1为换货，2为退货(货款下次抵扣)，3为退货退款');
			$table->text('reason')->default('')->comment('退货原因');
			$table->tinyInteger('status')->default(0)->comment('退货单状态，1为待审核，2为已驳回，3为已通过，4为已完成，5为已取消');
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
		Schema::dropIfExists('return_orders');
	}
}
