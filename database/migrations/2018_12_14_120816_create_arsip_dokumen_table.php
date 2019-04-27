<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArsipDokumenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arsip_dokumen', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_arsip_dokumen', 40)->primary();
			$table->string('id_arsip_loker', 40)->comment('FK: arsip_loker.id_arsip_loker');
			$table->string('id_arsip_pemilik', 40)->comment('FK: arsip_pemilik.id_arsip_pemilik');
			$table->string('id_arsip_subkategori', 40)->comment('FK: arsip_subkategori.id_arsip_subkategori');
			$table->string('id_unit_kerja', 40)->nullable()->comment('FK: unit_kerja.id_unit_kerja (opsional)');
			$table->string('kode_katalog', 64)->nullable();
			$table->string('nm_arsip_dokumen', 128)->nullable();
			$table->string('nomor_arsip_dokumen', 32)->nullable();
			$table->integer('jumlah_halaman')->nullable();
			$table->date('tgl_penyusunan')->nullable();
			$table->string('contact_person', 32)->nullable()->comment('nomor hp atau kontak dari penanggungjawab dokumen');
			$table->boolean('is_upload')->nullable()->comment('0 = belum upload; 1 = sudah upload;');
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
		Schema::drop('arsip_dokumen');
	}

}
