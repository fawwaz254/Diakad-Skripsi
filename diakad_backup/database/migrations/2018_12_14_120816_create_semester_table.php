<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSemesterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('semester', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_semester', 40)->primary();
			$table->string('nm_semester', 64)->nullable();
			$table->integer('thn_akademik_semester')->nullable();
			$table->string('tahun_ajaran', 12)->nullable();
			$table->boolean('is_aktif_semester')->nullable()->comment('0 = semester tidak aktif; 1 = semester yg sedang aktif;');
			$table->string('kode_semester', 16)->nullable();
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
		Schema::drop('semester');
	}

}
