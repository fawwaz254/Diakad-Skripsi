<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerpusKategoriBukuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perpus_kategori_buku', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_perpus_kategori_buku', 40)->primary();
			$table->string('nm_perpus_kategori_buku', 64)->nullable();
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
		Schema::drop('perpus_kategori_buku');
	}

}
