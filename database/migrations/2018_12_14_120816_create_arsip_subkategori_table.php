<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArsipSubkategoriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arsip_subkategori', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_arsip_subkategori', 40)->primary();
			$table->string('id_arsip_kategori', 40)->nullable()->comment('FK: arsip_kategori.id_arsip_kategori');
			$table->string('nm_arsip_subkategori', 64)->nullable();
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
		Schema::drop('arsip_subkategori');
	}

}
