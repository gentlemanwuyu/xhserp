<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collection_items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('collection_id')->default(0)->comment('收款单ID');
			$table->integer('doi_id')->default(0)->comment('出货单ItemID');
			$table->tinyInteger('is_full')->default(0)->comment('是否全额收款，0为否，1为是');
			$table->decimal('amount', 8, 2)->default(0.00)->comment('收款金额');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('最后更新时间');
			$table->index('collection_id');
			$table->index('doi_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('collection_items');
	}
}
