<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkSertifikasiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_sertifikasi', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_sertifikasi', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('jenis_sertifikasi', 64)->nullable();
			$table->string('nomor_sertifikasi', 64)->nullable();
			$table->integer('tahun_sertifikasi')->nullable();
			$table->string('bidang_studi_sertifikasi', 32)->nullable();
			$table->string('nrg_ptk', 64)->nullable()->comment('Nomor Registrasi Guru yang diperoleh PTK');
			$table->string('nomor_peserta_sertifikasi', 64)->nullable();
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
		Schema::drop('ptk_sertifikasi');
	}

}
