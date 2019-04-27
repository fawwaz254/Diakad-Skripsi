<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_siswa', 40)->primary();
			$table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
			$table->string('id_c_siswa', 40)->comment('FK: calon_siswa_baru.id_c_siswa (Siswa Lama Sebelum Siakad harus insert ke calon_siswa_baru terlebih dahulu)');
			$table->string('id_kelompok_biaya', 40)->comment('FK: kelompok_biaya.id_kelompok_biaya');
			$table->string('id_kelas', 40)->comment('FK: kelas.id_kelas');
			$table->string('id_wali_murid', 40)->nullable()->comment('FK: wali_murid.id_wali_murid (diisi ketika setting di role pendidikan)');
			$table->string('nis_siswa', 64)->nullable();
			$table->string('nisn_siswa', 64)->nullable();
			$table->boolean('thn_masuk_siswa')->nullable();
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
		Schema::drop('siswa');
	}

}
