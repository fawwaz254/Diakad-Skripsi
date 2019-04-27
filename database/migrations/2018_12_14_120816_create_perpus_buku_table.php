<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerpusBukuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perpus_buku', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_perpus_buku', 40)->primary();
			$table->string('id_perpus_almari', 40)->comment('FK: perpus_almari.id_perpus_almari');
			$table->string('id_perpus_subkategori_buku', 40)->comment('FK: perpus_subkategori_buku.id_perpus_subkategori_buku');
			$table->string('kode_perpus_buku', 64)->nullable();
			$table->string('nm_perpus_buku', 128)->nullable();
			$table->integer('tahun_perpus_buku')->nullable();
			$table->string('judul_perpus_buku', 128)->nullable();
			$table->integer('kota_penerbit_perpus_buku')->nullable()->comment('FK: kota.id_kota');
			$table->string('nm_penerbit_perpus_buku', 128)->nullable();
			$table->float('jumlah_perpus_buku', 10, 0)->nullable();
			$table->boolean('is_upload_cover')->nullable()->comment('1 = sudah upload cover buku; 0 = belum upload cover buku;');
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
		Schema::drop('perpus_buku');
	}

}
