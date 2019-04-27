<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrestasiSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prestasi_siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_prestasi_siswa', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('id_tingkat_prestasi_siswa', 40)->comment('FK: tingkat_prestasi_siswa.id_tingkat_prestasi_siswa');
			$table->string('id_guru_pendamping', 40)->nullable()->comment('FK: guru.id_guru (isian optional)');
			$table->string('id_ekskul', 40)->nullable()->comment('FK: ekskul.id_ekskul (isian optional)');
			$table->boolean('jenis_prestasi_siswa')->nullable()->comment('1 = Sains; 2 = Seni; 3 = Olahraga; 4 = Lain-lain;');
			$table->string('nm_prestasi_siswa', 64)->nullable()->comment('nama prestasi (misal 10 besar, juara 1, dll)');
			$table->string('lokasi_prestasi_siswa', 64)->nullable();
			$table->string('penyelenggara_prestasi_siswa', 128)->nullable();
			$table->boolean('peringkat_prestasi_siswa')->nullable();
			$table->date('tgl_prestasi_siswa')->nullable();
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
		Schema::drop('prestasi_siswa');
	}

}
