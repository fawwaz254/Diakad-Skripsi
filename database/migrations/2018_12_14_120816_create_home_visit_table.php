<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeVisitTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('home_visit', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_home_visit', 40)->primary();
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('id_guru_wali_kelas', 40)->comment('FK: guru.id_guru');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('nomor_hp_wali_murid', 32)->nullable();
			$table->string('alamat_wali_murid', 128)->nullable();
			$table->boolean('is_berkas_lengkap')->nullable()->comment('0 = berkas belum diterima/belum lengkap di kesiswaan; 1 = berkas sudah diterima/sudah lengkap di kesiswaan;');
			$table->string('id_guru_kesiswaan', 40)->nullable()->comment('guru kesiswaan yang melakukan validasi berkas home visit');
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
		Schema::drop('home_visit');
	}

}
