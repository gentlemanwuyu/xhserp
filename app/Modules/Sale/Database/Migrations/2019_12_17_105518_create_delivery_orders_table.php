<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_orders', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->default('')->comment('出货单编号');
			$table->integer('customer_id')->default(0)->comment('客户ID');
			$table->tinyInteger('delivery_method')->default(0)->comment('出货方式');
			$table->integer('express_id')->default(0)->comment('快递公司ID');
			$table->enum('is_collected', [0, 1])->default(0)->comment('是否代收');
			$table->decimal('collected_amount', 8, 2)->default(0.00)->comment('代收金额');
			$table->text('address')->default('')->comment('地址');
			$table->string('consignee')->default('')->comment('收货人');
			$table->string('consignee_phone')->default('')->comment('收货人电话');
			$table->text('note')->default('')->comment('备注');
			$table->integer('user_id')->default(0)->comment('创建人ID');
			$table->tinyInteger('status')->default(0)->comment('状态, 1为待出货, 2为完成');
			$table->timestamp('finished_at')->nullable()->comment('完成时间');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->timestamp('deleted_at')->nullable()->comment('删除时间');

			$table->index('code');
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
		Schema::dropIfExists('delivery_orders');
	}
}
