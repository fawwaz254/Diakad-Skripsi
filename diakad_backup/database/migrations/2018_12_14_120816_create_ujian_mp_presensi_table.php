<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianMpPresensiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian_mp_presensi', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ujian_mp_presensi', 40)->primary();
			$table->string('id_ujian_mp', 40)->comment('FK: ujian_mp.id_ujian_mp');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->boolean('kehadiran')->nullable()->comment('1 = hadir; 2 = sakit, tidak hadir; 3 = izin, tidak hadir; 4 = tanpa keterangan, tidak hadir;');
			$table->string('alasan', 256)->nullable();
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
		Schema::drop('ujian_mp_presensi');
	}

}
