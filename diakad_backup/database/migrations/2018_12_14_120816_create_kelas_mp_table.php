<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKelasMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kelas_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kelas_mp', 40)->primary();
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('id_kelas', 40)->comment('FK: kelas.id_kelas');
			$table->string('id_mata_pelajaran', 40)->comment('FK: mata_pelajaran.id_mata_pelajaran');
			$table->string('nm_kelas_mp', 32)->nullable();
			$table->boolean('jml_pertemuan_kelas_mp')->nullable();
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
		Schema::drop('kelas_mp');
	}

}
