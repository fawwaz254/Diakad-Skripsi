<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBeasiswaSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('beasiswa_siswa', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_beasiswa_siswa', 40)->primary();
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->boolean('jenis_beasiswa_siswa')->nullable()->comment('1 = anak berprestasi; 2 = anak miskin; 3 = pendidikan; 4 = unggulan; 99 = lain-lain;');
			$table->string('keterangan_beasiswa_siswa', 128)->nullable();
			$table->integer('tahun_mulai_beasiswa_siswa')->nullable();
			$table->integer('tahun_selesai_beasiswa_siswa')->nullable()->comment('apabila hanya satu tahun atau kurang, tahun_selesai diisi sama dengan tahun_mulai');
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
		Schema::drop('beasiswa_siswa');
	}

}
