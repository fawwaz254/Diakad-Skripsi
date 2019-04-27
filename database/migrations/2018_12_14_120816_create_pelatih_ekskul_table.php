<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePelatihEkskulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pelatih_ekskul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pelatih_ekskul', 40)->primary();
			$table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
			$table->string('nomor_hp_pelatih_ekskul', 32)->nullable();
			$table->string('alamat_pelatih_ekskul', 128)->nullable();
			$table->boolean('is_aktif')->nullable();
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
		Schema::drop('pelatih_ekskul');
	}

}
