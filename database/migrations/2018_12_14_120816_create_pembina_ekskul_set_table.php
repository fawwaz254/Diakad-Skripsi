<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePembinaEkskulSetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pembina_ekskul_set', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pembina_ekskul_set', 40)->primary();
			$table->string('id_guru', 40)->comment('FK: guru.id_guru');
			$table->string('id_ekskul', 40)->comment('FK: ekskul.id_ekskul');
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
		Schema::drop('pembina_ekskul_set');
	}

}
