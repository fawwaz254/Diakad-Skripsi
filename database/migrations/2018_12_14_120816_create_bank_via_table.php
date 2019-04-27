<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankViaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_via', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->boolean('id_bank_via')->primary();
			$table->string('kode_bank_via', 16)->nullable();
			$table->string('nm_bank_via', 32)->nullable();
			$table->boolean('is_aktif')->nullable();
			$table->timestamps();
			$table->string('created_by', 40)->nullable();
			$table->string('updated_by', 40)->nullable();
			$table->softDeletes();
			$table->string('deleted_by', 40)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bank_via');
	}

}
