<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKurikulumMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kurikulum_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kurikulum_mp', 40)->primary();
			$table->string('id_kurikulum', 40)->comment('FK: kurikulum.id_kurikulum');
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
		Schema::drop('kurikulum_mp');
	}

}
