<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRaporKelompokTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rapor_kelompok', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rapor_kelompok', 40)->primary();
			$table->string('nm_rapor_kelompok', 64)->nullable();
			$table->boolean('urutan_rapor_kelompok')->nullable();
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
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
		Schema::drop('rapor_kelompok');
	}

}
