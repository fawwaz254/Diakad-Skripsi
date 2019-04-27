<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJenisKepegawaianTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jenis_kepegawaian', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->boolean('id_jenis_kepegawaian')->primary();
			$table->string('kode_jenis_kepegawaian', 4)->nullable();
			$table->string('nm_jenis_kepegawaian', 32)->nullable();
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
		Schema::drop('jenis_kepegawaian');
	}

}
