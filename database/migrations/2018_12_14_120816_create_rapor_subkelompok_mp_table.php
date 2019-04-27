<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaporSubkelompokMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rapor_subkelompok_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rapor_subkelompok_mp', 40)->primary();
			$table->string('id_rapor_kelompok_mp', 40)->comment('FK: rapor_kelompok_mp.id_rapor_kelompok_mp');
			$table->string('id_mata_pelajaran', 40)->comment('FK: mata_pelajaran.id_mata_pelajaran');
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
		Schema::drop('rapor_subkelompok_mp');
	}

}
