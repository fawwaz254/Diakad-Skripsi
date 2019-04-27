<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJalurTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jalur', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jalur', 40)->primary();
			$table->string('nm_jalur', 64)->nullable();
			$table->string('kode_jalur', 16)->nullable();
			$table->string('id_sekolah', 40)->comment('FK:sekolah.id_sekolah');
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
		Schema::drop('jalur');
	}

}
