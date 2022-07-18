<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBentukPendidikanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bentuk_pendidikan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->boolean('id_bentuk_pendidikan')->primary();
			$table->string('kode_bentuk_pendidikan', 4)->nullable();
			$table->string('nm_bentuk_pendidikan', 8)->nullable();
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
		Schema::drop('bentuk_pendidikan');
	}

}
