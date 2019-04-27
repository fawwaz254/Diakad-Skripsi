<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKodeKegiatanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kode_kegiatan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('kode_kegiatan', 32)->primary();
			$table->string('keterangan', 64)->nullable();
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
		Schema::drop('kode_kegiatan');
	}

}
