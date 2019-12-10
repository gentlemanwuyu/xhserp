<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_orders', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->default('')->comment('订单编号');
			$table->integer('supplier_id')->default(0)->comment('供应商ID');
			$table->tinyInteger('payment_method')->default(0)->comment('付款方式');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->timestamp('deleted_at')->nullable()->comment('删除时间');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('purchase_orders');
	}
}
