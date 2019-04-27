<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePenerimaanPetugasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('penerimaan_petugas', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_penerimaan_petugas', 40)->primary();
			$table->string('id_penerimaan', 40)->comment('FK: penerimaan.id_penerimaan');
			$table->string('id_pengguna_petugas', 40)->comment('FK: pengguna.id_pengguna (guru atau staff)');
			$table->boolean('jabatan_petugas')->nullable()->comment('1 = admin; 2 = verifikator;');
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
		Schema::drop('penerimaan_petugas');
	}

}
