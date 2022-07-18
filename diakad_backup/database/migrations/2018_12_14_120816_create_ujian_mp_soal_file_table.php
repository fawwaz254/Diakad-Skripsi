<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianMpSoalFileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian_mp_soal_file', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ujian_mp_soal_file', 40)->primary();
			$table->string('id_ujian_mp_soal', 40)->comment('FK: ujian_mp_soal.id_ujian_mp_soal');
			$table->string('nm_soal_file', 128)->nullable();
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
		Schema::drop('ujian_mp_soal_file');
	}

}
