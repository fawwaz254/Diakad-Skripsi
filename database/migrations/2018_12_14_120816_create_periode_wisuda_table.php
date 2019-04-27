<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePeriodeWisudaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('periode_wisuda', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_periode_wisuda', 40)->primary();
			$table->string('id_wisuda', 40)->comment('FK: wisuda.id_wisuda');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('nm_periode_wisuda', 64)->nullable();
			$table->float('besar_biaya', 10, 0)->nullable();
			$table->date('tgl_bayar_mulai')->nullable();
			$table->date('tgl_bayar_selesai')->nullable();
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
		Schema::drop('periode_wisuda');
	}

}
