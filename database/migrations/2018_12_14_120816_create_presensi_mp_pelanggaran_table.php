<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePresensiMpPelanggaranTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('presensi_mp_pelanggaran', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_presensi_mp_pelanggaran', 40)->primary();
			$table->string('id_presensi_mp', 40)->comment('FK: presensi_mp.id_presensi_mp');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('catatan_pelanggaran', 256)->nullable();
			$table->boolean('is_sudah_tindakan')->nullable();
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
		Schema::drop('presensi_mp_pelanggaran');
	}

}
