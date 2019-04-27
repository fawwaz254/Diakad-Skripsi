<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianMpRuanganTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian_mp_ruangan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ujian_mp_ruangan', 40)->primary();
			$table->string('id_ujian_mp', 40)->comment('FK: ujian_mp.id_ujian_mp');
			$table->string('id_ruangan', 40)->comment('FK: ruangan.id_ruangan');
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
		Schema::drop('ujian_mp_ruangan');
	}

}
