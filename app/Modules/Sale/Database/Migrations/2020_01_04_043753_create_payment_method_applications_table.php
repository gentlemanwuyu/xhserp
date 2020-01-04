<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodApplicationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_method_applications', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('customer_id')->default(0)->comment('客户ID');
			$table->tinyInteger('payment_method')->default(0)->comment('付款方式，1为现金，2为货到付款，3为月结');
			$table->integer('credit')->default(0)->comment('信用额度，只针对货到付款');
			$table->tinyInteger('monthly_day')->unsigned()->default(0)->comment('月结天数');
			$table->text('reason')->default('')->comment('申请原因');
			$table->text('reject_reason')->default('')->comment('驳回原因');
			$table->integer('user_id')->default(0)->comment('申请人ID');
			$table->tinyInteger('status')->default(0)->comment('状态，1为待审核，2为已驳回，3为已通过');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->timestamp('deleted_at')->nullable()->comment('删除时间');
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
		Schema::dropIfExists('payment_method_applications');
	}
}
