<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('supplier_id')->default(0)->comment('供应商ID');
			$table->decimal('amount', 8, 2)->default(0.00)->comment('付款金额');
			$table->tinyInteger('method')->default(0)->comment('付款方式，1为现金，2为汇款，3为支票/汇票');
			$table->integer('pay_user_id')->default(0)->comment('付款人ID');
			$table->integer('account_id')->default(0)->comment('收款账户ID');
			$table->decimal('remained_amount', 8, 2)->default(0.00)->comment('剩余金额');
			$table->tinyInteger('is_finished')->default(0)->comment('是否完全抵扣，0为否，1为是');
			$table->integer('user_id')->default(0)->comment('创建人ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->timestamp('deleted_at')->nullable()->comment('删除时间');
			$table->index('supplier_id');
			$table->index('method');
			$table->index('pay_user_id');
			$table->index('account_id');
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
		Schema::dropIfExists('payments');
	}
}
