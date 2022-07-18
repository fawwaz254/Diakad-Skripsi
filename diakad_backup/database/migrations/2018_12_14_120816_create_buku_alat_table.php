<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBukuAlatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('buku_alat', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_buku_alat', 40)->primary();
			$table->string('id_jenis_buku_alat', 40)->comment('FK: jenis_buku_alat.id_jenis_buku_alat');
			$table->string('nm_buku_alat', 64)->nullable()->comment('Diisi judul buku atau nama alat yang relevan');
			$table->integer('tingkat_pendidikan_buku_alat')->nullable()->comment('diisi khusus untuk buku');
			$table->string('id_mata_pelajaran', 40)->nullable()->comment('FK: mata_pelajaran.id_mata_pelajaran (diisi null apabila umum)');
			$table->integer('jumlah_buku_alat')->nullable();
			$table->integer('jumlah_kondisi_baik')->nullable();
			$table->integer('jumlah_kondisi_rusak')->nullable();
			$table->string('keterangan_buku_alat', 128)->nullable();
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
		Schema::drop('buku_alat');
	}

}
