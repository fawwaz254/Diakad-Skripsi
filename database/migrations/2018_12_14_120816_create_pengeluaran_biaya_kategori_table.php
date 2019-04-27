<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePengeluaranBiayaKategoriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengeluaran_biaya_kategori', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengeluaran_biaya_kategori', 40)->primary();
			$table->string('nm_pengeluaran_biaya_kategori', 128)->nullable();
			$table->string('id_sekolah', 40)->nullable()->comment('FK: sekolah.id_sekolah');
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
		Schema::drop('pengeluaran_biaya_kategori');
	}

}
