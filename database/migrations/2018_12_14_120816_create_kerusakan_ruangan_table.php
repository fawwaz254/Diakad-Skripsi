<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKerusakanRuanganTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kerusakan_ruangan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->boolean('id_kerusakan_ruangan')->primary();
			$table->string('kode_kerusakan_ruangan', 4)->nullable();
			$table->string('nm_kerusakan_ruangan', 64)->nullable();
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
		Schema::drop('kerusakan_ruangan');
	}

}
