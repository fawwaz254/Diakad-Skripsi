<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKebutuhanKhususTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kebutuhan_khusus', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->boolean('id_kebutuhan_khusus')->primary();
			$table->string('kode_kebutuhan_khusus', 4)->nullable();
			$table->string('nm_kebutuhan_khusus', 64)->nullable();
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
		Schema::drop('kebutuhan_khusus');
	}

}
