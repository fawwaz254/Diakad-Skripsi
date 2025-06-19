<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePeriodeMagangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('periode_magang', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_periode_magang', 40)->primary();
			$table->string('id_magang', 40)->comment('FK: magang.id_magang');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('nm_periode_magang', 64)->nullable();
			$table->string('nomor_sk_periode_magang', 64)->nullable();
			$table->decimal('besar_biaya', 10, 0)->nullable();
			$table->date('tgl_magang_mulai')->nullable();
			$table->date('tgl_magang_selesai')->nullable();
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
		Schema::drop('periode_magang');
	}

}
