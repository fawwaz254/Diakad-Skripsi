<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEkskulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ekskul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ekskul', 40)->primary();
			$table->string('nm_ekskul', 64)->nullable();
			$table->string('nomor_sk_ekskul', 64)->nullable();
			$table->date('tgl_sk_ekskul')->nullable();
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
		Schema::drop('ekskul');
	}

}
