<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ujian_mp', 40)->primary();
			$table->string('id_kegiatan', 40)->comment('FK: kegiatan.id_kegiatan');
			$table->string('id_kelas_mp', 40)->comment('FK: kelas_mp.id_kelas_mp');
			$table->string('nm_ujian_mp', 128)->nullable();
			$table->date('tgl_ujian_mp')->nullable();
			$table->string('jam_mulai', 5)->nullable();
			$table->string('jam_selesai', 5)->nullable();
			$table->string('keterangan', 128)->nullable();
			$table->boolean('is_online')->nullable()->comment('0 = offline; 1 = online;');
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
		Schema::drop('ujian_mp');
	}

}
