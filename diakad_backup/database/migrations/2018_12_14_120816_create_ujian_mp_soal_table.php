<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianMpSoalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian_mp_soal', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ujian_mp_soal', 40)->primary();
			$table->string('id_ujian_mp', 40)->comment('FK: ujian_mp.id_ujian_mp');
			$table->text('isi_soal', 65535)->nullable();
			$table->text('isi_pilihan_a', 65535)->nullable();
			$table->text('isi_pilihan_b', 65535)->nullable();
			$table->text('isi_pilihan_c', 65535)->nullable();
			$table->text('isi_pilihan_d', 65535)->nullable();
			$table->text('isi_pilihan_e', 65535)->nullable();
			$table->text('isi_pilihan_f', 65535)->nullable();
			$table->boolean('kunci_pilihan')->nullable()->comment('kunci jawaban pilihan (1 = A; 2 = B, 3 = C; 4 = D; 5 = E; 6 = F)');
			$table->text('kunci_esai', 65535)->nullable()->comment('kunci jawaban esai');
			$table->integer('nomor_soal')->nullable()->comment('nomor soal urut apabila is_random_nomor = 1');
			$table->boolean('is_random_soal')->nullable()->comment('0 = soal urut order by nomor_soal; 1 = soal random tidak sesuai urutan nomor soal;');
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
		Schema::drop('ujian_mp_soal');
	}

}
