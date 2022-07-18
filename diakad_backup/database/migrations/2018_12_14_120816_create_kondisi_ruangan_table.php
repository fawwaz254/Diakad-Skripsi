<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKondisiRuanganTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kondisi_ruangan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kondisi_ruangan', 40)->primary();
			$table->string('id_ruangan', 40)->comment('FK: ruangan.id_ruangan');
			$table->boolean('id_kerusakan_ruangan')->comment('FK: kerusakan_ruangan.id_kerusakan_ruangan');
			$table->float('persentase_kerusakan_ruangan', 10, 0)->nullable();
			$table->string('keterangan_kerusakan_ruangan', 64)->nullable();
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
		Schema::drop('kondisi_ruangan');
	}

}
