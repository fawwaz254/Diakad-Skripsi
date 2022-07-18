<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePemilikSarprasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pemilik_sarpras', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pemilik_sarpras', 40)->primary();
			$table->string('kode_pemilik_sarpras', 16)->nullable();
			$table->string('nm_pemilik_sarpras', 64)->nullable();
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
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
		Schema::drop('pemilik_sarpras');
	}

}
