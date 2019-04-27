<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalonSiswaOrtuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calon_siswa_ortu', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_c_siswa', 40)->primary();
			$table->string('nm_ayah', 128)->nullable();
			$table->string('nik_ayah', 64)->nullable();
			$table->date('tgl_lahir_ayah')->nullable();
			$table->boolean('id_jenis_pendidikan_ayah')->nullable()->comment('FK: jenis_pendidikan.id_jenis_pendidikan');
			$table->boolean('id_jenis_pekerjaan_ayah')->nullable()->comment('FK: pekerjaan_ortu.id_pekerjaan_ortu');
			$table->boolean('id_jenis_penghasilan_ayah')->nullable()->comment('FK: penghasilan_ortu.id_penghasilan_ortu');
			$table->boolean('id_kebutuhan_khusus_ayah')->nullable()->comment('FK: kebutuhan_khusus.id_kebutuhan_khusus');
			$table->string('nm_ibu', 128)->nullable();
			$table->string('nik_ibu', 64)->nullable();
			$table->date('tgl_lahir_ibu')->nullable();
			$table->boolean('id_jenis_pendidikan_ibu')->nullable()->comment('FK: jenis_pendidikan.id_jenis_pendidikan');
			$table->boolean('id_jenis_pekerjaan_ibu')->nullable()->comment('FK: pekerjaan_ortu.id_pekerjaan_ortu');
			$table->boolean('id_jenis_penghasilan_ibu')->nullable()->comment('FK: penghasilan_ortu.id_penghasilan_ortu');
			$table->boolean('id_kebutuhan_khusus_ibu')->nullable()->comment('FK: kebutuhan_khusus.id_kebutuhan_khusus');
			$table->string('nm_wali', 128)->nullable();
			$table->string('nik_wali', 64)->nullable();
			$table->date('tgl_lahir_wali')->nullable();
			$table->boolean('id_jenis_pendidikan_wali')->nullable()->comment('FK: jenis_pendidikan.id_jenis_pendidikan');
			$table->boolean('id_jenis_pekerjaan_wali')->nullable()->comment('FK: pekerjaan_ortu.id_pekerjaan_ortu');
			$table->boolean('id_jenis_penghasilan_wali')->nullable()->comment('FK: penghasilan_ortu.id_penghasilan_ortu');
			$table->boolean('id_kebutuhan_khusus_wali')->nullable()->comment('FK: kebutuhan_khusus.id_kebutuhan_khusus');
			$table->string('alamat_jalan_ortu', 128)->nullable();
			$table->string('alamat_dusun_ortu', 64)->nullable();
			$table->string('alamat_kelurahan_ortu', 64)->nullable();
			$table->string('almat_rt_ortu', 4)->nullable();
			$table->string('alamat_rw_ortu', 4)->nullable();
			$table->string('alamat_kecamatan_ortu', 64)->nullable();
			$table->string('alamat_kodepos_ortu', 8)->nullable();
			$table->string('alamat_kota_ortu', 40)->nullable()->comment('FK: kota.id_kota');
			$table->boolean('alamat_provinsi_ortu')->nullable()->comment('FK: provinsi.id_provinsi');
			$table->string('nomor_telp_ortu', 16)->nullable();
			$table->string('nomor_hp_ortu', 32)->nullable();
			$table->string('email_ortu', 64)->nullable();
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
		Schema::drop('calon_siswa_ortu');
	}

}
