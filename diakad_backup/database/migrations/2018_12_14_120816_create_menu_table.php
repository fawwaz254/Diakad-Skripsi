<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('id_menu', true);
			$table->integer('id_modul')->comment('FK: modul.id_modul');
			$table->string('nm_menu', 32)->nullable();
			$table->string('page', 64)->nullable();
			$table->boolean('urutan')->nullable();
			$table->boolean('akses')->nullable()->default(0)->comment('Default Akses');
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
		Schema::drop('menu');
	}

}
