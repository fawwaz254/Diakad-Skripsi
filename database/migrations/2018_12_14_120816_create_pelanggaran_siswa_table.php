<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePelanggaranSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pelanggaran_siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pelanggaran_siswa', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_guru_input', 40)->nullable()->comment('FK: guru.id_guru (guru yg melakukan input pelanggaran) >> null apabila yg input adalah staff');
			$table->string('catatan_pelanggaran', 256)->nullable();
			$table->string('catatan_pelanggaran_khusus', 256)->nullable()->comment('private konseling dari individu BK (tidak dapat diakses oleh aktor yg lain)');
			$table->timestamp('tgl_pelanggaran')->nullable();
			$table->boolean('aktor_input_pelanggaran')->nullable()->comment('1 = Role BK; 2 = Role Kesiswaan; 3 = Wali Kelas; (Guru Mapel ada di tabel presensi_mp_pelanggaran)');
			$table->boolean('is_sudah_tindakan')->nullable()->comment('0 = belum ada tindakan; 1 = sudah ada tindakan;');
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
		Schema::drop('pelanggaran_siswa');
	}

}
