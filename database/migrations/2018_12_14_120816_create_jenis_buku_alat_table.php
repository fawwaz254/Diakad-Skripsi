<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJenisBukuAlatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jenis_buku_alat', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jenis_buku_alat', 40)->primary();
			$table->string('kode_jenis_buku_alat', 16)->nullable();
			$table->string('nm_jenis_buku_alat', 64)->nullable();
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
		Schema::drop('jenis_buku_alat');
	}

}
