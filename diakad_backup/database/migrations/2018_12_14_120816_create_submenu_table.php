<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubmenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('submenu', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('id_submenu', true);
			$table->integer('id_menu')->comment('FK: menu.id_menu');
			$table->string('nm_submenu', 32);
			$table->string('page', 64);
			$table->boolean('urutan');
			$table->boolean('akses')->default(0)->comment('Default Akses');
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
		Schema::drop('submenu');
	}

}
