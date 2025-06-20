<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRuanganTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ruangan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ruangan', 40)->primary();
			$table->string('id_jenis_ruangan', 40)->comment('FK: jenis_ruangan.id_jenis_ruangan');
			$table->string('id_pemilik_sarpras', 40)->comment('FK: pemilik_sarpras.id_pemilik_sarpras');
			$table->string('id_gedung', 40)->comment('FK: gedung.id_gedung');
			$table->string('nm_ruangan', 64)->nullable();
			$table->decimal('panjang_ruangan', 10, 0)->nullable()->comment('dalam satuan meter(boleh dalam desimal)');
			$table->decimal('lebar_ruangan', 10, 0)->nullable()->comment('dalam satuan meter(boleh dalam desimal)');
			$table->boolean('kapasitas_ruangan')->nullable()->comment('kapasitas untuk KBM biasa');
			$table->boolean('kapasitas_ujian')->nullable()->comment('kapasitas untuk ujian');
			$table->string('deskripsi_ruangan', 128)->nullable();
			$table->boolean('is_perpustakaan')->nullable()->comment('1 = Ya; 0 = Tidak;');
			$table->string('nomor_registrasi_perpustakaan', 128)->nullable()->comment('Nomor registrasi perpustakaan yang tercatat pada Perpustakaan Nasional Republik Indonesia (PNRI). Untuk mendapatkan nomor registrasi ini, sekolah dapat mendaftarkan perpustakaannya secara daring (online) melalui situs http://npp.pnri.go.id. Nomor registrasi hanya diisi untuk prasarana yang berjenis perpustakaan');
			$table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
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
		Schema::drop('ruangan');
	}

}
