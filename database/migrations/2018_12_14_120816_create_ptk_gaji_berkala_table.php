<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkGajiBerkalaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_gaji_berkala', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_gaji_berkala', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('pangkat_golongan_gaji_berkala', 64)->nullable();
			$table->string('nomor_sk_gaji_berkala', 64)->nullable();
			$table->date('tgl_sk_gaji_berkala')->nullable();
			$table->date('tgl_mulai_gaji_berkala')->nullable()->comment('Tanggal mulai berlakunya gaji baru sesuai SK kenaikan gaji berkala');
			$table->integer('masa_kerja_tahun_gaji_berkala')->nullable();
			$table->integer('masa_kerja_bulan_gaji_berkala')->nullable();
			$table->decimal('gaji_pokok', 10, 0)->nullable()->comment('Jumlah gaji pokok baru sesuai yang tertera pada SK kenaikan gaji berkala');
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
		Schema::drop('ptk_gaji_berkala');
	}

}
