<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSekolahTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sekolah', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_sekolah', 40)->primary()->comment('[prefix].strtotime(now).uniqid()');
			$table->string('nm_sekolah', 64)->nullable();
			$table->string('prefix', 5)->nullable();
			$table->string('http_host', 64)->nullable();
			$table->string('url_ppdb_online', 64)->nullable();
			$table->string('url_ujian_online', 64)->nullable();
			$table->string('url_kantin_online', 64)->nullable();
			$table->string('url_perpus_online', 64)->nullable();
			$table->boolean('is_subscribe')->nullable()->comment('1 = subscribe; 2 = server vps;');
			$table->string('npsn_sekolah', 16)->nullable()->comment('Nomor Pokok Sekolah Nasional');
			$table->boolean('id_bentuk_pendidikan')->nullable()->comment('FK: bentuk_pendidikan.id_bentuk_pendidikan');
			$table->string('alamat_jalan', 128)->nullable();
			$table->string('alamat_kelurahan', 64)->nullable();
			$table->string('alamat_kecamatan', 64)->nullable();
			$table->integer('alamat_kota')->nullable()->comment('FK: kota.id_kota');
			$table->boolean('alamat_provinsi')->nullable()->comment('FK: provinsi.id_provinsi');
			$table->string('alamat_rt', 4)->nullable();
			$table->string('alamat_rw', 4)->nullable();
			$table->string('alamat_dusun', 64)->nullable();
			$table->string('alamat_kodepos', 8)->nullable();
			$table->string('alamat_latitude', 128)->nullable()->comment('posisi geografis garis lintang (diisi dari hasil pinned di maps)');
			$table->string('alamat_longitude', 128)->nullable()->comment('posisi geografis garis bujur (diisi dari hasil pinned di maps)');
			$table->string('nomor_sk_pendirian_sekolah', 64)->nullable();
			$table->date('tgl_sk_pendirian_sekolah')->nullable();
			$table->boolean('status_kepemilikan')->nullable()->comment('1 = pemerintah pusat; 2 = pemerintah daerah; 3 = yayasan; 4 = lainnya;');
			$table->string('nm_yayasan_sekolah', 128)->nullable()->comment('diisi apabila status_kepemilikan = 3 (yayasan)');
			$table->string('nomor_sk_izin_operasional', 64)->nullable();
			$table->date('tgl_sk_izin_operasional')->nullable();
			$table->boolean('is_mbs')->nullable()->comment('manajemen berbasis sekolah (1 = Ya; 0 = Tidak;)');
			$table->float('luas_tanah_milik_sekolah', 10, 0)->nullable()->comment('luas tanah yg dipakai oleh sekolah dengan kepemilikan sendiri; dalam meter persegi (m2)');
			$table->float('luas_tanah_non_milik_sekolah', 10, 0)->nullable()->comment('luas tanah yg dipakai oleh sekolah namun bukan kepemilikan sendiri; dalam meter persegi (m2)');
			$table->string('nm_wajib_pajak_sekolah', 64)->nullable();
			$table->string('npwp_sekolah', 64)->nullable();
			$table->string('nomor_telp_sekolah', 16)->nullable();
			$table->string('nomor_fax_sekolah', 16)->nullable();
			$table->string('email_sekolah', 64)->nullable();
			$table->string('website_sekolah', 64)->nullable()->comment('diisi alamt website .sch.id');
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
		Schema::drop('sekolah');
	}

}
