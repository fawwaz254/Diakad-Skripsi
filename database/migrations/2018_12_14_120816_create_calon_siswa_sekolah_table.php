<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalonSiswaSekolahTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calon_siswa_sekolah', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_c_siswa', 40)->primary();
			$table->string('nm_sekolah_asal', 128)->nullable();
			$table->string('id_kota_sekolah_asal', 40)->nullable()->comment('FK:kota.id_kota');
			$table->string('nomor_shun', 64)->nullable();
			$table->float('nilai_shun', 10, 0)->nullable();
			$table->string('nomor_ijasah', 64)->nullable();
			$table->integer('tahun_lulus')->nullable();
			$table->string('nomor_peserta_unas', 32)->nullable();
			$table->string('nisn', 128)->nullable();
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
		Schema::drop('calon_siswa_sekolah');
	}

}
