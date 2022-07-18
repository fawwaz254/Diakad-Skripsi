<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkBukuDitulisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_buku_ditulis', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_buku_ditulis', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('judul_buku', 128)->nullable();
			$table->integer('tahun_buku')->nullable();
			$table->string('penerbit_buku', 64)->nullable()->comment('Nama penerbit yang telah mempublikasikan buku yang ditulis oleh PTK. Isikan Independen, apabila buku tersebut diterbitkan mandiri oleh PTK (self-publishing)');
			$table->string('isbn_buku', 64)->nullable()->comment('ISBN (International Standard Book Number) atas buku yang pernah ditulis oleh PTK (jika ada)');
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
		Schema::drop('ptk_buku_ditulis');
	}

}
