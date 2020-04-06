<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuEntryExchangesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sku_entry_exchanges', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('entry_id')->default(0)->comment('入库ID');
			$table->integer('purchase_return_order_item_id')->default(0)->comment('采购退货单ItemID');
			$table->integer('quantity')->default(0)->comment('数量');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('entry_id');
			$table->index('purchase_return_order_item_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sku_entry_exchanges');
	}
}
