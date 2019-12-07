<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierContactsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_contacts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('supplier_id')->default(0)->comment('供应商id');
			$table->string('name')->default('')->comment('名称');
			$table->string('position')->default('')->comment('职位');
			$table->string('phone')->default('')->comment('电话');
			$table->index('supplier_id');
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
		Schema::dropIfExists('supplier_contacts');
	}
}
