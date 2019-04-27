<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKomplainSarprasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('komplain_sarpras', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_komplain_sarpras', 40)->primary();
			$table->string('id_ruangan', 40)->nullable()->comment('FK: ruangan.id_ruangan (diisi apabila komplain bukan kepada buku alat)');
			$table->string('id_inventaris_ruangan', 40)->nullable()->comment('FK: inventaris_ruangan.id_inventaris_ruangan (dikosongkan apabila komplain kepada ruangan atau buku alat)');
			$table->string('id_buku_alat', 40)->nullable()->comment('FK: buku_alat.id_buku_alat (diisi apabila komplain kepada buku atau alat)');
			$table->string('id_siswa_komplain', 40)->nullable()->comment('FK: siswa.id_siswa (opsional komplain dari siswa atau guru)');
			$table->string('id_guru_komplain', 40)->nullable()->comment('FK: guru.id_guru  (opsional komplain dari siswa atau guru)');
			$table->string('keterangan_komplain', 128)->nullable();
			$table->boolean('is_urgent')->nullable()->comment('0 = tidak urgent; 1 = urgent; 2 = sangat urgent;');
			$table->boolean('is_sudah_perbaikan')->nullable()->comment('0 = belum perbaikan; 1 = sudah perbaikan; (di update oleh bagian sarpras)');
			$table->string('id_guru_sarpras', 40)->nullable()->comment('FK: guru.id_guru (guru bagian sarpras yang melaporkan/melakukan perbaikan)');
			$table->string('keterangan_perbaikan', 128)->nullable();
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
		Schema::drop('komplain_sarpras');
	}

}
