<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInformasiPpdbTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('informasi_ppdb', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_informasi_ppdb', 40)->primary();
			$table->string('id_pengguna_input', 40)->comment('FK: pengguna.id_pengguna (created_by/updated_by)');
			$table->text('isi_informasi', 65535)->nullable();
			$table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
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
		Schema::drop('informasi_ppdb');
	}

}
