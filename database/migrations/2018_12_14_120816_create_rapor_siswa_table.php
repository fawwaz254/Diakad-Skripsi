<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaporSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rapor_siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rapor_siswa', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('id_rapor_deskripsi', 40)->nullable()->comment('kategori SIKAP, PENGETAHUAN, KETERAMPILAN, PENGETAHUAN_DAN_KETERAMPILAN, EKSKUL');
			$table->integer('jumlah_sakit')->nullable()->comment('kategori KETIDAKHADIRAN');
			$table->integer('jumlah_izin')->nullable()->comment('kategori KETIDAKHADIRAN');
			$table->integer('jumlah_tanpa_keterangan')->nullable()->comment('kategori KETIDAKHADIRAN');
			$table->string('id_prestasi_siswa', 40)->nullable()->comment('kategori PRESTASI');
			$table->text('deskripsi_catatan_wali_kelas', 65535)->nullable()->comment('kategori CATATAN_WALI_KELAS');
			$table->decimal('nilai_kkm', 10, 0)->nullable()->comment('diisi ketika join id_rapor_deskripsi dan hanya id_rapor_kelompok_mp yg not null');
			$table->decimal('nilai_angka', 10, 0)->nullable()->comment('diisi ketika join id_rapor_deskripsi dan hanya id_rapor_kelompok_mp yg not null');
			$table->string('nilai_huruf', 3)->nullable()->comment('diisi ketika join id_rapor_deskripsi dan hanya id_rapor_kelompok_mp yg not null');
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
		Schema::drop('rapor_siswa');
	}

}
