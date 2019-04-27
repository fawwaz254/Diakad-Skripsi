<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNilaiEkskulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nilai_ekskul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_nilai_ekskul', 40)->primary();
			$table->string('id_pengambilan_ekskul', 40)->comment('FK: pengambilan_ekskul.id_pengambilan_ekskul');
			$table->string('id_komponen_ekskul', 40)->comment('FK: komponen_ekskul.id_komponen_ekskul');
			$table->float('besar_nilai_ekskul', 10, 0)->nullable();
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
		Schema::drop('nilai_ekskul');
	}

}
