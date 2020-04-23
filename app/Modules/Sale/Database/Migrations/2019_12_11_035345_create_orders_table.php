<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->default('')->comment('订单编号');
			$table->integer('customer_id')->default(0)->comment('客户ID');
			$table->tinyInteger('payment_method')->default(0)->comment('付款方式');
			$table->tinyInteger('tax')->default(0)->comment('税率，1为不含税，2为3%，3为17%');
			$table->char('currency_code', 3)->default('')->comment('币种');
			$table->date('delivery_date')->nullable()->default(null)->comment('交期');
			$table->tinyInteger('status')->default(0)->comment('订单状态，1为待审核，2为已驳回，3为已通过，4为已完成，5为已取消');
			$table->tinyInteger('payment_status')->default(0)->comment('付款状态，1为待付款，2为完成付款');
			$table->tinyInteger('exchange_status')->default(0)->comment('换货状态，1为待换货');
			$table->integer('user_id')->default(0)->comment('创建人ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->timestamp('deleted_at')->nullable()->comment('删除时间');

			$table->index('code');
			$table->index('customer_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('orders');
	}
}
