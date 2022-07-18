<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePengeluaranBiayaSubkategoriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengeluaran_biaya_subkategori', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengeluaran_biaya_subkategori', 40)->primary();
			$table->string('id_pengeluaran_biaya_kategori', 40)->nullable()->comment('FK: pengeluaran_biaya_kategori.id_pengeluaran_biaya_kategori');
			$table->string('nm_pengeluaran_biaya_subkategori', 128)->nullable();
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
		Schema::drop('pengeluaran_biaya_subkategori');
	}

}
