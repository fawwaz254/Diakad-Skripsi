<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkRiwayatKarirTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_riwayat_karir', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_riwayat_karir', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->boolean('id_bentuk_pendidikan')->nullable()->comment('FK: bentuk_pendidikan.id_bentuk_pendidikan');
			$table->boolean('id_jenis_lembaga_pengangkat')->nullable()->comment('FK: jenis_lembaga_pengangkat.id_jenis_lembaga_pengangkat');
			$table->boolean('id_jenis_kepegawaian')->nullable()->comment('FK: jenis_kepegawaian.id_jenis_kepegawaian');
			$table->boolean('id_jenis_ptk')->nullable()->comment('FK: jenis_ptk.id_jenis_ptk');
			$table->string('nm_jenis_lembaga_pengangkat', 64)->nullable()->comment('Nama lembaga yang mengangkat PTK sebagai guru. Apabila lembaga adalah disdikbud kabupaten/kota, diisi dengan nama dinas terkait. Apabila lembaga adalah sekolah, diisi dengan nama sekolah terkait');
			$table->string('nomor_sk_kerja', 64)->nullable();
			$table->date('tgl_sk_kerja')->nullable();
			$table->date('tgl_mulai_kerja')->nullable();
			$table->date('tgl_selesai_kerja')->nullable();
			$table->integer('id_kota_kerja')->nullable()->comment('FK: kota.id_kota');
			$table->string('ttd_sk_kerja', 64)->nullable()->comment('Nama pejabat yang menandatangani SK pengangkatan PTK sebagai guru pada lembaga terkait');
			$table->string('mapel_diajarkan', 64)->nullable();
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
		Schema::drop('ptk_riwayat_karir');
	}

}
