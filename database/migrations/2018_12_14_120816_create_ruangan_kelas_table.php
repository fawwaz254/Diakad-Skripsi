<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRuanganKelasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ruangan_kelas', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ruangan_kelas', 40)->primary();
			$table->string('id_kelas', 40)->comment('FK:kelas.id_kelas');
			$table->string('id_ruangan', 40)->comment('FK: ruangan.id_ruangan');
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
		Schema::drop('ruangan_kelas');
	}

}
