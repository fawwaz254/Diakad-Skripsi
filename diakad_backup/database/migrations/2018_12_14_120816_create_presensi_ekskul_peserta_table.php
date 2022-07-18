<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePresensiEkskulPesertaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('presensi_ekskul_peserta', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_presensi_ekskul_peserta', 40)->primary();
			$table->string('id_presensi_ekskul', 40)->comment('FK: presensi_ekskul.id_presensi_ekskul');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->boolean('kehadiran')->comment('1 = hadir; 2 = sakit, tidak hadir; 3 = izin, tidak hadir; 4 = tanpa keterangan, tidak hadir;');
			$table->string('alasan', 256)->comment('diisi apabila tidak hadir');
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
		Schema::drop('presensi_ekskul_peserta');
	}

}
