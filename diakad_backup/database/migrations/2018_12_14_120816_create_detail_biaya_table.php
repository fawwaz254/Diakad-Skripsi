<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDetailBiayaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('detail_biaya', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_detail_biaya', 40)->primary();
			$table->string('id_biaya_sekolah', 40)->comment('FK: biaya_sekolah.id_biaya_sekolah');
			$table->string('id_biaya', 40)->comment('FK: biaya.id_biaya');
			$table->boolean('validasi_biaya')->nullable()->comment('0 = belum di validasi; 1 = sudah di validasi;');
			$table->float('besar_biaya', 10, 0)->nullable();
			$table->string('keterangan_biaya', 128)->nullable();
			$table->boolean('jenis_detail_biaya')->nullable()->comment('5 = biaya per kegiatan; 4 = biaya per bulan; 3 = satu kali optional; 2 = satu kali pendaftaran; 1 = tiap semester bayar;');
			$table->boolean('id_bulan')->nullable()->comment('1 = Januari, 2 = Februari, dst > diisi ketika jenis biaya per bulan');
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
		Schema::drop('detail_biaya');
	}

}
