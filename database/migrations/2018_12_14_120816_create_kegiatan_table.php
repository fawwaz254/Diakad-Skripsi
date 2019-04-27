<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKegiatanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kegiatan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kegiatan', 40)->primary();
			$table->string('nm_kegiatan', 256)->nullable();
			$table->string('deskripsi_kegiatan', 256)->nullable();
			$table->string('kode_kegiatan', 32)->nullable()->comment('FK: kode_kegiatan.kode_kegiatan');
			$table->string('id_sekolah', 40)->comment('FK:sekolah.id_sekolah');
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
		Schema::drop('kegiatan');
	}

}
