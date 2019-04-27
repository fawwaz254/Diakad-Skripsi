<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalonSiswaPrestasiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calon_siswa_prestasi', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_c_siswa_prestasi', 40)->primary();
			$table->string('id_c_siswa', 40)->comment('FK: calon_siswa_baru.id_c_siswa');
			$table->string('id_tingkat_prestasi_siswa', 40)->comment('FK: tingkat_prestasi_siswa.id_tingkat_prestasi_siswa');
			$table->boolean('jenis_prestasi_c_siswa')->nullable();
			$table->string('nm_prestasi_c_siswa', 64)->nullable();
			$table->string('lokasi_prestasi_c_siswa', 64)->nullable();
			$table->string('penyelenggara_prestasi_c_siswa', 128)->nullable();
			$table->boolean('peringkat_prestasi_c_siswa')->nullable();
			$table->date('tgl_prestasi_c_siswa')->nullable();
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
		Schema::drop('calon_siswa_prestasi');
	}

}
