<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkPenghargaanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_penghargaan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_penghargaan', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('tingkat_penghargaan', 32)->nullable();
			$table->string('jenis_penghargaan', 32)->nullable();
			$table->string('nm_penghargaan', 64)->nullable();
			$table->integer('tahun_penghargaan')->nullable();
			$table->string('instansi_penghargaan', 64)->nullable();
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
		Schema::drop('ptk_penghargaan');
	}

}
