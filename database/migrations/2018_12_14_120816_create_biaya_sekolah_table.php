<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBiayaSekolahTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('biaya_sekolah', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_biaya_sekolah', 40)->primary();
			$table->string('id_kelompok_biaya', 40)->comment('FK: kelompok_biaya.id_kelompok_biaya');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('id_jalur', 40)->nullable()->comment('FK: jalur.id_jalur &gt; optional biaya khusus jalur tertentu');
			$table->float('besar_biaya_sekolah', 10, 0)->nullable();
			$table->boolean('validasi_biaya_sekolah')->nullable()->comment('0 = belum di validasi; 1 = sudah di validasi;');
			$table->integer('keterangan_biaya_sekolah')->nullable();
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
		Schema::drop('biaya_sekolah');
	}

}
