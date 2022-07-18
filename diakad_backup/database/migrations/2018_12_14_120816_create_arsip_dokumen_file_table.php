<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArsipDokumenFileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arsip_dokumen_file', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_arsip_dokumen_file', 40)->primary();
			$table->string('id_arsip_dokumen', 40)->nullable()->comment('FK: arsip_dokumen.id_arsip_dokumen');
			$table->string('nm_arsip_dokumen_file', 128)->nullable();
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
		Schema::drop('arsip_dokumen_file');
	}

}
