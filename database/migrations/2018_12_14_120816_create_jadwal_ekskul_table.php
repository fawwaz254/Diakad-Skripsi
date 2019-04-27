<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJadwalEkskulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jadwal_ekskul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jadwal_ekskul', 40)->primary();
			$table->string('id_ekskul', 40)->comment('FK: ekskul.id_ekskul');
			$table->string('id_jadwal_hari', 40)->comment('FK: jadwal_hari.id_jadwal_hari');
			$table->string('id_jadwal_jam', 40)->comment('FK: jadwal_jam.id_jadwal_jam');
			$table->string('tempat_jadwal_ekskul', 128)->nullable();
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
		Schema::drop('jadwal_ekskul');
	}

}
