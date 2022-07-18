<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSekretarisKelasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sekretaris_kelas', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_sekretaris_kelas', 40)->primary();
			$table->string('id_kelas', 40)->comment('FK:kelas.id_kelas');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_semester', 40)->comment('FK:semester.id_semester');
			$table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
			$table->timestamps();
			$table->string('created_by', 28)->nullable();
			$table->string('updated_by', 28)->nullable();
			$table->softDeletes();
			$table->string('deleted_by', 28)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sekretaris_kelas');
	}

}
