<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_order_items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->default(0)->comment('订单ID');
			$table->integer('product_id')->default(0)->comment('产品ID');
			$table->integer('sku_id')->default(0)->comment('SKU ID');
			$table->string('title')->default('')->comment('标题');
			$table->string('unit')->default('')->comment('单位');
			$table->integer('quantity')->default(0)->comment('数量');
			$table->decimal('price', 8, 2)->default(0.00)->comment('单价');
			$table->date('delivery_date')->nullable()->default(null)->comment('交期');
			$table->string('note')->default('')->comment('备注');
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
		Schema::dropIfExists('purchase_order_items');
	}
}
