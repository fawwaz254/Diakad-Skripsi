<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkDiklatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_diklat', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_diklat', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('jenis_diklat', 64)->nullable();
			$table->string('nm_diklat', 64)->nullable();
			$table->string('nomor_sertifikat_diklat', 64)->nullable();
			$table->string('penyelenggara_diklat', 64)->nullable();
			$table->integer('tahun_diklat')->nullable();
			$table->string('peran_diklat', 32)->nullable()->comment('Peran PTK dalam diklat yang pernah diikuti, misalnya sebagai Pemateri, Narasumber, Peserta, atau Panitia');
			$table->string('tingkat_diklat', 32)->nullable();
			$table->integer('lama_jam_diklat')->nullable()->comment('Lamanya penyelenggaraan diklat dalam satuan jam. Umumnya dapat dilihat pada sertifikat yang diterbitkan oleh penyelenggara');
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
		Schema::drop('ptk_diklat');
	}

}
