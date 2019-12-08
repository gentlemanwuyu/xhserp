<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContactsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_contacts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('customer_id')->default(0)->comment('客户id');
			$table->string('name')->default('')->comment('名称');
			$table->string('position')->default('')->comment('职位');
			$table->string('phone')->default('')->comment('电话');
			$table->index('customer_id');
			$table->index('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('customer_contacts');
	}
}
