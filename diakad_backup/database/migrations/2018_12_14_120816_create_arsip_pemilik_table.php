<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArsipPemilikTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arsip_pemilik', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_arsip_pemilik', 40)->primary();
			$table->string('id_unit_kerja', 40)->nullable()->comment('FK: unit_kerja.id_unit_kerja (opsional)');
			$table->string('nm_arsip_pemilik', 64)->nullable();
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
		Schema::drop('arsip_pemilik');
	}

}
