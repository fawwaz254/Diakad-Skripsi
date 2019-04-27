<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJenisGedungTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jenis_gedung', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_jenis_gedung', 40)->primary();
			$table->string('nm_jenis_gedung', 32)->nullable();
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
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
		Schema::drop('jenis_gedung');
	}

}
