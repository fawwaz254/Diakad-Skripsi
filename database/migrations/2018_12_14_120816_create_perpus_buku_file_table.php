<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePerpusBukuFileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perpus_buku_file', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_perpus_buku_file', 40)->primary();
			$table->string('id_perpus_buku', 40)->nullable()->comment('FK: perpus_buku.id_perpus_buku');
			$table->string('nm_perpus_buku_file', 128)->nullable();
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
		Schema::drop('perpus_buku_file');
	}

}
