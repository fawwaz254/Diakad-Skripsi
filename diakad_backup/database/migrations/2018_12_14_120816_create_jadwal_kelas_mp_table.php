<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJadwalKelasMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jadwal_kelas_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jadwal_kelas_mp', 40)->primary();
			$table->string('id_kelas_mp', 40)->comment('FK: kelas_mp.id_kelas_mp');
			$table->string('id_ruangan', 40)->comment('FK: ruangan.id_ruangan');
			$table->boolean('id_jadwal_hari')->comment('FK: jadwal_hari.id_jadwal_hari');
			$table->string('id_jadwal_jam', 40)->comment('FK: jadwal_jam.id_jadwal_jam');
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
		Schema::drop('jadwal_kelas_mp');
	}

}
