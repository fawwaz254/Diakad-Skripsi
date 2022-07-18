<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJadwalJamTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jadwal_jam', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jadwal_jam', 40)->primary();
			$table->string('nm_jadwal_jam', 16)->nullable();
			$table->string('jam_mulai', 2)->nullable();
			$table->string('menit_mulai', 2)->nullable();
			$table->string('jam_selesai', 2)->nullable();
			$table->string('menit_selesai', 2)->nullable();
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
		Schema::drop('jadwal_jam');
	}

}
