<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTindakanPelanggaranTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tindakan_pelanggaran', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_tindakan_pelanggaran', 40)->primary();
			$table->string('id_pelanggaran_siswa', 40)->nullable()->comment('FK: pelanggaran_siswa.id_pelanggaran_siswa &gt; diisi tergantung sumber pelanggaran');
			$table->string('id_presensi_mp_pelanggaran', 40)->nullable()->comment('FK: presensi_mp_pelanggaran.id_presensi_mp_pelanggaran &gt; diisi tergantung sumber pelanggaran');
			$table->string('id_jenis_tindakan', 40)->comment('FK: jenis_tindakan.id_jenis_tindakan');
			$table->string('catatan_tindakan_pelanggaran', 256)->nullable();
			$table->string('catatan_tindakan_pelanggaran_khusus', 256)->nullable()->comment('private konseling dari individu BK (tidak dapat diakses oleh aktor yg lain)');
			$table->timestamp('tgl_tindakan_pelanggaran')->nullable();
			$table->boolean('aktor_input_tindakan_pelanggaran')->nullable()->comment('1 = Role BK; 2 = Role Kesiswaan;');
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
		Schema::drop('tindakan_pelanggaran');
	}

}
