<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkAnakTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_anak', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_anak', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('nm_anak', 64)->nullable();
			$table->string('status_anak', 16)->nullable();
			$table->string('jenjang_pendidikan_anak', 8)->nullable()->comment('Jenjang pendidikan anak PTK saat ini');
			$table->string('nisn_anak', 64)->nullable();
			$table->boolean('jenis_kelamin_anak')->nullable()->comment('1 = Laki-laki; 2 = Perempuan;');
			$table->integer('id_kota_lahir_anak')->nullable()->comment('FK: kota.id_kota');
			$table->date('tgl_lahir_anak')->nullable();
			$table->integer('tahun_masuk_pendidikan_anak')->nullable()->comment('Tahun masuk sekolah sesuai dengan jenjang pendidikan anak PTK saat ini');
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
		Schema::drop('ptk_anak');
	}

}
