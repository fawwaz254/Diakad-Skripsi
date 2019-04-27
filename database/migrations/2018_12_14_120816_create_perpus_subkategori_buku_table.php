<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerpusSubkategoriBukuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perpus_subkategori_buku', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_perpus_subkategori_buku', 40)->primary();
			$table->string('id_perpus_kategori_buku', 40)->nullable()->comment('FK: perpus_kategori_buku.id_perpus_kategori_buku');
			$table->string('nm_perpus_subkategori_buku', 64)->nullable();
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
		Schema::drop('perpus_subkategori_buku');
	}

}
