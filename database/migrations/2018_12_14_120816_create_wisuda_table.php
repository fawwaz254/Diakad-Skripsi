<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWisudaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wisuda', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_wisuda', 40)->primary();
			$table->string('nm_wisuda', 64)->nullable();
			$table->string('keterangan_wisuda', 128)->nullable();
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah (ada di DB lain)');
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
		Schema::drop('wisuda');
	}

}
