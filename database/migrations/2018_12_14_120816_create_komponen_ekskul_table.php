<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKomponenEkskulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('komponen_ekskul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_komponen_ekskul', 40)->primary();
			$table->string('id_ekskul', 40)->comment('FK: ekskul.id_ekskul');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('nm_komponen_ekskul', 32)->nullable();
			$table->float('persentase_komponen_ekskul', 10, 0)->nullable();
			$table->boolean('urutan_komponen_ekskul')->nullable();
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
		Schema::drop('komponen_ekskul');
	}

}
