<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('id_role', true);
			$table->string('nm_role', 64)->nullable();
			$table->string('deskripsi_role', 256)->nullable();
			$table->string('tipe_role', 1)->nullable();
			$table->string('path', 64)->nullable();
			$table->boolean('is_mobile')->nullable();
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
		Schema::drop('role');
	}

}
