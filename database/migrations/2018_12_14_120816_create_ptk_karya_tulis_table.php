<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkKaryaTulisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_karya_tulis', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_karya_tulis', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('judul_karya_tulis', 64)->nullable();
			$table->integer('tahun_pembuatan_karya_tulis')->nullable();
			$table->string('publikasi_karya_tulis', 64)->nullable()->comment('Diisi tempat dipublikasikannya karya tulis yang pernah dibuat oleh PTK. Untuk skripsi dan tesis, diisi dengan nama universitas');
			$table->string('keterangan_karya_tulis', 128)->nullable();
			$table->string('url_publikasi_karya_tulis', 128)->nullable()->comment('Diisi alamat situs/link/tautan publikasi karya ilmiah oleh PTK (apabila dipublikasikan atau bisa diperoleh secara daring/online)');
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
		Schema::drop('ptk_karya_tulis');
	}

}
