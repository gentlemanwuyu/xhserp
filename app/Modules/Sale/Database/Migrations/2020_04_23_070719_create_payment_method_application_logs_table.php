<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodApplicationLogsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_method_application_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('payment_method_application_id')->default(0)->comment('付款申请ID');
			$table->integer('customer_id')->default(0)->comment('客户ID');
			$table->string('message', 1024)->default('')->comment('日志信息');
			$table->text('content')->default('')->comment('日志内容');
			$table->integer('user_id')->default(0)->comment('用户ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('payment_method_application_id', 'payment_method_application_id');
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
		Schema::dropIfExists('payment_method_application_logs');
	}
}
