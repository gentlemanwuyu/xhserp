<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuEntryDeductionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sku_entry_deductions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sku_entry_id')->default(0)->comment('SKU入库ID');
			$table->integer('payment_id')->default(0)->comment('付款单ID');
			$table->integer('purchase_return_order_id')->default(0)->comment('退货单ID');
			$table->decimal('amount', 8, 2)->default(0.00)->comment('抵扣金额');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('sku_entry_id');
			$table->index('payment_id');
			$table->index('purchase_return_order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sku_entry_deductions');
	}
}
