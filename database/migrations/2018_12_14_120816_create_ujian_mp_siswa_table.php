<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianMpSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian_mp_siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ujian_mp_siswa', 40)->primary();
			$table->string('id_ujian_mp_soal', 40)->comment('FK: ujian_mp_soal.id_ujian_mp_soal');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->boolean('jawaban_pilihan')->nullable();
			$table->text('jawaban_esai', 65535)->nullable();
			$table->timestamp('tgl_entry_jawaban')->nullable()->comment('tgl dan jam ketika siswa input jawaban atau terakhir kali merubah jawabannya');
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
		Schema::drop('ujian_mp_siswa');
	}

}
