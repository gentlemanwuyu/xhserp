<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderItemDeductionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_order_item_deductions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('delivery_order_item_id')->default(0)->comment('出货单ItemID');
			$table->char('order_currency_code', 3)->default('')->comment('订单币种，冗余字段');
			$table->decimal('order_rate', 18, 10)->default(0)->comment('订单币种相对人民币汇率');
			$table->integer('collection_id')->default(0)->comment('收款单ID');
			$table->integer('return_order_id')->default(0)->comment('退货单ID');
			$table->char('deduct_currency_code', 3)->default('')->comment('抵扣币种，冗余字段');
			$table->decimal('deduct_rate', 18, 10)->default(0)->comment('订单币种相对人民币汇率');
			$table->decimal('amount', 8, 2)->default(0.00)->comment('抵扣金额(人民币)');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('delivery_order_item_id');
			$table->index('collection_id');
			$table->index('return_order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('delivery_order_item_deductions');
	}
}
