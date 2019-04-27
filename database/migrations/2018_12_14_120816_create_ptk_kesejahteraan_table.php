<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkKesejahteraanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_kesejahteraan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_kesejahteraan', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('jenis_kesejahteraan', 64)->nullable()->comment('Jenis kesejahteraan atau santunan yang pernah atau sedang diterima PTK');
			$table->string('nm_kesejahteraan', 64)->nullable()->comment('Nama santunan yang diterima PTK');
			$table->string('penyelenggara_kesejahteraan', 64)->nullable();
			$table->integer('tahun_mulai_kesejahteraan')->nullable();
			$table->integer('tahun_selesai_kesejahteraan')->nullable();
			$table->string('status_kesejahteraan', 32)->nullable();
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
		Schema::drop('ptk_kesejahteraan');
	}

}
