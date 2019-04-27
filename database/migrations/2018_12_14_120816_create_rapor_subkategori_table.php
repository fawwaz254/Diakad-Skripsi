<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaporSubkategoriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rapor_subkategori', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rapor_subkategori', 40)->primary();
			$table->string('id_rapor_kategori', 40)->comment('FK: rapor_kategori.id_rapor_kategori');
			$table->string('nm_rapor_subkategori', 128)->nullable();
			$table->boolean('urutan_rapor_subkategori')->nullable();
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
		Schema::drop('rapor_subkategori');
	}

}
