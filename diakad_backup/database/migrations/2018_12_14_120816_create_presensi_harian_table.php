<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePresensiHarianTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('presensi_harian', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_presensi_harian', 40)->primary();
			$table->string('id_kelas', 40)->comment('FK: kelas.id_kelas');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->boolean('id_jadwal_hari')->comment('FK: jadwal_hari.id_jadwal_hari');
			$table->string('id_siswa_entry', 40)->comment('FK: siswa.id_siswa');
			$table->dateTime('tgl_entry')->nullable();
			$table->float('persentase_presensi_harian', 10, 0)->nullable();
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
		Schema::drop('presensi_harian');
	}

}
