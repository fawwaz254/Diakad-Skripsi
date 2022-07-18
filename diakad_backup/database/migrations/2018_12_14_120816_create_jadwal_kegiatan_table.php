<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJadwalKegiatanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jadwal_kegiatan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jadwal_kegiatan', 40)->primary();
			$table->string('id_kegiatan', 40)->comment('FK:kegiatan.id_kegiatan');
			$table->string('id_semester', 40)->comment('FK:semester.id_semester');
			$table->date('tgl_mulai')->nullable();
			$table->date('tgl_selesai')->nullable();
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
		Schema::drop('jadwal_kegiatan');
	}

}
