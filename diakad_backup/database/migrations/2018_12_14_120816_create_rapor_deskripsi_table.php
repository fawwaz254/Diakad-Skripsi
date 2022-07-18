<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaporDeskripsiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rapor_deskripsi', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rapor_deskripsi', 40)->primary();
			$table->string('id_rapor_subkategori', 40)->nullable()->comment('(diisi salah satu) (kategori SIKAP)');
			$table->string('id_rapor_kelompok_mp', 40)->nullable()->comment('(diisi salah satu) (kategori PENGETAHUAN, KETERAMPILAN, PENGETAHUAN_DAN_KETERAMPILAN)');
			$table->string('id_rapor_subkelompok_mp', 40)->nullable()->comment('(diisi salah satu) (kategori PENGETAHUAN, KETERAMPILAN, PENGETAHUAN_DAN_KETERAMPILAN)');
			$table->string('id_ekstrakurikuler', 40)->nullable()->comment('(diisi salah satu) (kategori EKSKUL)');
			$table->string('predikat_rapor_deskripsi', 3)->nullable()->comment('A, B, C, dll');
			$table->text('deskripsi_rapor', 65535)->nullable();
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
		Schema::drop('rapor_deskripsi');
	}

}
