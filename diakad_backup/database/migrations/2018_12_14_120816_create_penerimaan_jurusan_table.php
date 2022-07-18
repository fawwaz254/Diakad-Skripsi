<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePenerimaanJurusanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('penerimaan_jurusan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_penerimaan_jurusan', 40)->primary();
			$table->string('id_penerimaan', 40)->comment('FK: penerimaan.id_penerimaan');
			$table->string('id_jurusan', 40)->comment('FK: jurusan.id_jurusan');
			$table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
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
		Schema::drop('penerimaan_jurusan');
	}

}
