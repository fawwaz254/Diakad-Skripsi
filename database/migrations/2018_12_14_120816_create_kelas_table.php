<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKelasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kelas', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kelas', 40)->primary();
			$table->string('id_jurusan', 40)->comment('FK:jurusan.id_jurusan');
			$table->string('nm_kelas', 128)->nullable();
			$table->boolean('tingkat')->nullable();
			$table->string('keterangan_kelas', 256)->nullable();
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
		Schema::drop('kelas');
	}

}
