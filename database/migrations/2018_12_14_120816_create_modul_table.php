<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModulTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('modul', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('id_modul', true);
			$table->integer('id_role')->comment('FK: role.id_role');
			$table->string('nm_modul', 32)->nullable();
			$table->string('route', 64)->nullable();
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
		Schema::drop('modul');
	}

}
