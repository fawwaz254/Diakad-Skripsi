<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKomponenMagangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('komponen_magang', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_komponen_magang', 40)->primary();
			$table->string('id_periode_magang', 40)->comment('FK: periode_magang.id_periode_magang');
			$table->string('nm_komponen_magang', 64)->nullable();
			$table->float('persentase_komponen_magang', 10, 0)->nullable();
			$table->boolean('urutan_komponen_magang')->nullable();
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
		Schema::drop('komponen_magang');
	}

}
