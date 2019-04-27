<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJenisLayakPipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jenis_layak_pip', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->boolean('id_jenis_layak_pip')->primary();
			$table->string('kode_jenis_layak_pip', 4)->nullable();
			$table->string('nm_jenis_layak_pip', 64)->nullable();
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
		Schema::drop('jenis_layak_pip');
	}

}
