<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryLogsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sku_id')->default(0)->comment('SkuID');
			$table->integer('product_id')->default(0)->comment('产品ID');
			$table->string('message', 1024)->default('')->comment('日志信息');
			$table->text('content')->default('')->comment('日志内容');
			$table->integer('user_id')->default(0)->comment('用户ID');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');

			$table->index('sku_id');
			$table->index('product_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('inventory_logs');
	}
}
