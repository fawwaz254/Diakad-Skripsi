<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMataPelajaranTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mata_pelajaran', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_mata_pelajaran', 40)->primary();
			$table->string('id_jurusan', 40)->comment('FK: jurusan.id_jurusan');
			$table->string('id_jenis_mata_pelajaran', 40)->comment('FK: jenis_mata_pelajaran.id_jenis_mata_pelajaran');
			$table->string('kd_mata_pelajaran', 32);
			$table->string('nm_mata_pelajaran', 64);
			$table->string('nm_mata_pelajaran_en', 64)->nullable();
			$table->boolean('kredit_semester')->nullable();
			$table->boolean('kredit_tatap_muka')->nullable()->default(0);
			$table->boolean('kredit_praktikum')->nullable()->default(0);
			$table->boolean('kredit_tutor')->nullable()->default(0);
			$table->boolean('kredit_prak_lapangan')->nullable()->default(0);
			$table->boolean('kredit_simulasi')->nullable()->default(0);
			$table->boolean('tingkat_semester')->nullable();
			$table->float('nilai_kkm', 10, 0)->nullable();
			$table->boolean('ada_sap')->nullable()->default(0);
			$table->boolean('ada_silabus')->nullable()->default(0);
			$table->boolean('ada_bahan_ajar')->nullable()->default(0);
			$table->boolean('ada_diktat')->nullable()->default(0);
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
		Schema::drop('mata_pelajaran');
	}

}
