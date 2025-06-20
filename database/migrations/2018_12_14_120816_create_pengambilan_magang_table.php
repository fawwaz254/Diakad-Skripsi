<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePengambilanMagangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengambilan_magang', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengambilan_magang', 40)->primary();
			$table->string('id_periode_magang', 40)->comment('FK: periode_magang.id_periode_magang');
			$table->string('id_rekanan_magang', 40)->comment('FK: rekanan_magang.id_rekanan_magang');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->boolean('status_apv_pengambilan_magang')->nullable()->comment('0 = belum approve; 1 = sudah approve;');
			$table->decimal('nilai_angka', 10, 0)->nullable();
			$table->string('nilai_huruf', 3)->nullable();
			$table->boolean('is_tampil')->nullable()->comment('0 = belum tampil ke siswa dan wali murid; 1 = sudah tampil ke siswa dan wali murid;');
			$table->boolean('status_magang')->nullable()->comment('0 = proses; 1 = selesai; 10 = batal;');
			$table->string('keterangan_batal', 64)->nullable()->comment('diisi ketika status_magang = 10 (batal)');
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
		Schema::drop('pengambilan_magang');
	}

}
