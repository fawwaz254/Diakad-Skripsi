<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaporKategoriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rapor_kategori', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rapor_kategori', 40)->primary();
			$table->string('kode_rapor_kategori', 64)->nullable()->comment('SIKAP, PENGETAHUAN, KETERAMPILAN, PENGETAHUAN_DAN_KETERAMPILAN, 	EKSKUL, KETIDAKHADIRAN, PRESTASI, CATATAN_WALI_KELAS, TANGGAPAN_ORTU');
			$table->string('nm_rapor_kategori', 128)->nullable();
			$table->boolean('urutan_rapor_kategori')->nullable();
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
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
		Schema::drop('rapor_kategori');
	}

}
