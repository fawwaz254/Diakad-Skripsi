<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNilaiMagangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nilai_magang', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_nilai_magang', 40)->primary();
			$table->string('id_pengambilan_magang', 40)->comment('FK: pengambilan_magang.id_pengambilan_magang');
			$table->string('id_komponen_magang', 40)->comment('FK: komponen_magang.id_komponen_magang');
			$table->decimal('besar_nilai_magang', 10, 0)->nullable();
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
		Schema::drop('nilai_magang');
	}

}
