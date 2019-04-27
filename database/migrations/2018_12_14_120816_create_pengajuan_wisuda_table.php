<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePengajuanWisudaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengajuan_wisuda', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengajuan_wisuda', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_periode_wisuda', 40)->comment('FK: periode_wisuda.id_periode_wisuda');
			$table->boolean('status_biodata')->nullable()->default(0)->comment('0 = belum lengkap; 1 = sudah lengkap;');
			$table->boolean('status_lab')->nullable()->default(0)->comment('0 = ada tanggungan; 1 = bebas tanggungan;');
			$table->boolean('status_perpus')->nullable()->default(0)->comment('0 = ada tanggungan; 1 = bebas tanggungan;');
			$table->boolean('status_ijasah')->nullable()->default(0)->comment('0 = belum cetak; 1 = sudah cetak dan diterima siswa;');
			$table->string('nomor_sk_kelulusan', 64)->nullable();
			$table->date('tgl_sk_kelulusan')->nullable();
			$table->string('nomor_ijasah', 64)->nullable();
			$table->date('tgl_kelulusan')->nullable();
			$table->float('ipk', 10, 0)->nullable();
			$table->dateTime('tgl_pengajuan_wisuda')->nullable()->comment('sysdate ketika melakukan pengajuan wisuda');
			$table->boolean('status_wisuda')->nullable()->comment('1 = proses; 2 = selesai (lulus); 3 = batal;');
			$table->string('keterangan_batal', 128)->nullable()->comment('diisi hanya ketika siswa batal wisuda (status_wisuda = 3)');
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
		Schema::drop('pengajuan_wisuda');
	}

}
