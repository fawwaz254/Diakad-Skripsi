<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKotaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kota', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('id_kota', true);
			$table->boolean('id_provinsi')->comment('FK:provinsi.id_provinsi');
			$table->string('nm_kota', 128)->nullable();
			$table->string('kode_kota', 64)->nullable();
			$table->string('tipe_dati2', 64)->nullable();
			$table->integer('is_aktif')->nullable();
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
		Schema::drop('kota');
	}

}
