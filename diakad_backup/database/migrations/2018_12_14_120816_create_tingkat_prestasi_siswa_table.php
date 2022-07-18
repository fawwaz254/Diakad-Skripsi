<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTingkatPrestasiSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tingkat_prestasi_siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_tingkat_prestasi_siswa', 40)->primary();
			$table->string('nm_tingkat_prestasi_siswa', 32)->nullable();
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
		Schema::drop('tingkat_prestasi_siswa');
	}

}
