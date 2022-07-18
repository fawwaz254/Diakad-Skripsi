<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkRiwayatPangkatGolonganTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_riwayat_pangkat_golongan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_riwayat_pangkat_golongan', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('nm_pangkat_golongan', 64)->nullable();
			$table->string('nomor_sk_pangkat_golongan', 64)->nullable();
			$table->date('tgl_sk_pangkat_golongan')->nullable();
			$table->date('tmt_pangkat_golongan')->nullable()->comment('TMT pangkat sesuai yang tertera pada SK kenaikan pangkat PTK');
			$table->integer('masa_kerja_tahun_pangkat_golongan')->nullable();
			$table->integer('masa_kerja_bulan_pangkat_golongan')->nullable();
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
		Schema::drop('ptk_riwayat_pangkat_golongan');
	}

}
