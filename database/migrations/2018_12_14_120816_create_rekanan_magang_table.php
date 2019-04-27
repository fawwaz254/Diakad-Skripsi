<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRekananMagangTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rekanan_magang', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_rekanan_magang', 40)->primary();
			$table->string('nm_rekanan_magang', 64)->nullable();
			$table->string('nomor_telp_rekanan_magang', 16)->nullable();
			$table->string('nomor_hp_rekanan_magang', 32)->nullable();
			$table->string('alamat_rekanan_magang', 128)->nullable();
			$table->date('tgl_awal_kerjasama')->nullable();
			$table->date('tgl_akhir_kerjasama')->nullable();
			$table->integer('kuota_rekanan_magang')->nullable();
			$table->string('contact_person_rekanan_magang', 32)->nullable()->comment('nama cp yang bisa dihubungi');
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
		Schema::drop('rekanan_magang');
	}

}
