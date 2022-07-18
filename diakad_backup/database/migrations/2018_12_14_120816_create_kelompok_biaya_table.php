<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKelompokBiayaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kelompok_biaya', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_kelompok_biaya', 40)->primary();
			$table->string('nm_kelompok_biaya', 128)->nullable();
			$table->string('keterangan_kelompok_biaya', 256)->nullable();
			$table->boolean('status_kelompok_biaya')->nullable()->comment('1 = reguler; 2 = khusus;');
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
		Schema::drop('kelompok_biaya');
	}

}
