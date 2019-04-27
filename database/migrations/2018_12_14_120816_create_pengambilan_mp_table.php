<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePengambilanMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pengambilan_mp', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_pengambilan_mp', 40)->primary();
			$table->string('id_kelas_mp', 40)->comment('FK: kelas_mp.id_kelas_mp');
			$table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->boolean('status_apv_pengambilan_mp')->nullable();
			$table->float('nilai_angka', 10, 0)->nullable();
			$table->string('nilai_huruf', 3)->nullable();
			$table->float('persentase_presensi', 10, 0)->nullable()->comment('akumulasi dari presensi_mp');
			$table->float('nilai_bobot', 10, 0)->nullable()->comment('Jam KBM x Nilai Indeks (BELUM TERPAKAI)');
			$table->float('nilai_angka_rapor', 10, 0)->nullable()->comment('nilai yg ditampilkan di rapor akhir');
			$table->string('nilai_huruf_rapor', 3)->nullable()->comment('nilai yg ditampilkan di rapor akhir');
			$table->float('nilai_angka_keterampilan', 10, 0)->nullable()->comment('nilai yg ditampilkan di rapor akhir');
			$table->string('nilai_huruf_keterampilan', 3)->nullable()->comment('nilai yg ditampilkan di rapor akhir');
			$table->boolean('is_tampil')->nullable()->comment('0 = belum ditampilkan ke siswa; 1 = ditampilkan ke siswa;');
			$table->boolean('is_transfer')->nullable()->default(0)->comment('0 = nilai reguler; 1 = nilai transfer;');
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
		Schema::drop('pengambilan_mp');
	}

}
