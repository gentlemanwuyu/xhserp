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
			$table->tinyInteger('tax')->default(0)->comment('税率，1为不含税，2为3%，3为17%');
			$table->tinyInteger('status')->default(0)->comment('订单状态，1为待审核，2为已驳回，3为已通过，4为已完成，5为已取消');
			$table->integer('user_id')->default(0)->comment('创建人ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->timestamp('deleted_at')->nullable()->comment('删除时间');

			$table->index('code');
			$table->index('supplier_id');
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
