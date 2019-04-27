<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkBeasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_beasiswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_beasiswa', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('jenis_beasiswa', 64)->nullable();
			$table->string('keterangan_beasiswa', 128)->nullable();
			$table->integer('tahun_mulai_beasiswa')->nullable();
			$table->integer('tahun_selesai_beasiswa')->nullable();
			$table->boolean('is_masih_menerima')->nullable()->comment('1 = Ya; 0 = Tidak;');
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
		Schema::drop('ptk_beasiswa');
	}

}
