<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerpusAlmariTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perpus_almari', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_perpus_almari', 40)->primary();
			$table->string('id_ruangan', 40)->comment('FK: ruangan.id_ruangan (where is_perpustakaan = 1)');
			$table->string('kode_perpus_almari', 16)->nullable();
			$table->string('nm_perpus_almari', 64)->nullable();
			$table->string('deskripsi_perpus_almari', 128)->nullable();
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
		Schema::drop('perpus_almari');
	}

}
