<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSekolahKebutuhanKhususTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sekolah_kebutuhan_khusus', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_sekolah_kebutuhan_khusus', 40)->primary();
			$table->string('id_sekolah', 40)->nullable()->comment('FK: sekolah.id_sekolah');
			$table->boolean('id_kebutuhan_khusus')->nullable()->comment('FK: kebutuhan_khusus.id_kebutuhan_khusus (kebutuhan khusus yg bisa ditangani oleh sekolah)');
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
		Schema::drop('sekolah_kebutuhan_khusus');
	}

}
