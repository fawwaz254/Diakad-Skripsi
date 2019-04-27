<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGedungTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gedung', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_gedung', 40)->primary();
			$table->string('id_jenis_gedung', 40)->comment('FK: jenis_gedung.id_jenis_gedung');
			$table->string('kode_gedung', 32)->nullable();
			$table->string('nm_gedung', 64)->nullable();
			$table->string('lokasi_gedung', 128)->nullable();
			$table->string('deskripsi_gedung', 128)->nullable();
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah (ada di DB lain)');
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
		Schema::drop('gedung');
	}

}
