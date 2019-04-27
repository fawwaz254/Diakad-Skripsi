<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalonSiswaFisikTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calon_siswa_fisik', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_c_siswa', 40)->primary();
			$table->float('tinggi_badan', 10, 0)->nullable()->comment('dalam cm');
			$table->float('berat_badan', 10, 0)->nullable()->comment('dalam kg');
			$table->boolean('is_berjilbab')->nullable()->comment('0 = tidak berjilbab; 1 = berjilbab;');
			$table->boolean('is_buta_warna')->nullable()->comment('0 = tidak buta warna; 1 = buta warna;');
			$table->string('ukuran_baju', 4)->nullable()->comment('S/M/L/XL dll');
			$table->string('riwayat_penyakit', 128)->nullable();
			$table->string('golongan_darah', 4)->nullable()->comment('A/B/O/AB');
			$table->string('riwayat_kelainan_jasmani', 128)->nullable();
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
		Schema::drop('calon_siswa_fisik');
	}

}
