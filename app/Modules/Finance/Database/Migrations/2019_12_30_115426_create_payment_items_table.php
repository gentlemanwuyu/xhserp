<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('payment_id')->default(0)->comment('付款单ID');
			$table->integer('entry_id')->default(0)->comment('入库记录ID');
			$table->decimal('amount', 8, 2)->default(0.00)->comment('抵扣金额');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->index('payment_id');
			$table->index('entry_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('payment_items');
	}
}
