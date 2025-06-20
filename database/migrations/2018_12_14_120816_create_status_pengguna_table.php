<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatusPenggunaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('status_pengguna', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_status_pengguna', 40)->primary();
			$table->integer('status_join_table')->comment('1 = Pegawai; 2 = Guru; 3 = Siswa;  4 = Wali Murid; 5 = Pelatih Ekskul;');
			$table->string('nm_status_pengguna', 64)->nullable();
			$table->boolean('aktif_status_pengguna')->nullable()->comment('0 = status keluar/non-aktif; 1 = status aktif;');
			$table->string('kode_status_pengguna', 32)->nullable();
			$table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah (ada di DB lain)');
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
		Schema::drop('status_pengguna');
	}

}
