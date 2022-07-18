<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalonSiswaBaruTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calon_siswa_baru', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_c_siswa', 40)->primary();
			$table->string('id_penerimaan', 40);
			$table->string('kode_voucher', 32)->nullable()->comment('kode voucher / nomor pendaftaran yg diperoleh dari sistem PPDB online');
			$table->string('password', 256)->nullable()->comment('password generate dari sistem PPDB online');
			$table->string('nm_c_siswa', 128)->nullable();
			$table->string('nik_siswa', 64)->nullable()->comment('nomor induk kependudukan yg tercantum pada kartu keluarga');
			$table->boolean('jenis_kelamin')->nullable()->comment('1 = Laki-laki; 2 = Perempuan;');
			$table->string('nisn_siswa', 16)->nullable()->comment('diisi nisn di sekolah sebelumnya (jika ada)');
			$table->boolean('id_agama')->nullable()->comment('FK: agama.id_agama');
			$table->integer('id_kota_lahir')->nullable()->comment('FK: kota.id_kota');
			$table->date('tgl_lahir')->nullable();
			$table->integer('id_kota_ksk')->nullable()->comment('FK: kota.id_kota');
			$table->string('nomor_ksk', 64)->nullable();
			$table->string('nomor_identitas', 64)->nullable()->comment('nomor KTP, SIM, atau lainnya');
			$table->string('nomor_akta_lahir', 64)->nullable()->comment('nomor registrasi akta lahir (umumnya tercantum pada bagian tengah atas lembar kutipan akta kelahiran)');
			$table->boolean('kewarganegaraan')->nullable()->comment('1 = WNI; 2 = WNA;');
			$table->string('nm_kewarganegaraan', 32)->nullable()->comment('diisi apabila kewarganegaraan = 2 (WNA)');
			$table->boolean('id_kebutuhan_khusus')->nullable()->comment('FK: kebutuhan_khusus.id_kebutuhan_khusus');
			$table->string('alamat_jalan', 128)->nullable();
			$table->string('alamat_dusun', 64)->nullable();
			$table->string('alamat_kelurahan', 64)->nullable();
			$table->string('alamat_rt', 4)->nullable();
			$table->string('alamat_rw', 4)->nullable();
			$table->string('alamat_kecamatan', 64)->nullable();
			$table->string('alamat_kodepos', 8)->nullable();
			$table->integer('alamat_kota')->nullable()->comment('FK: kota.id_kota');
			$table->boolean('alamat_provinsi')->nullable()->comment('FK: provinsi.id_provinsi');
			$table->string('alamat_latitude', 128)->nullable()->comment('posisi geografis garis lintang (diisi dari hasil pinned di maps)');
			$table->string('alamat_longitude', 128)->nullable()->comment('posisi geografis garis bujur (diisi dari hasil pinned di maps)');
			$table->string('nomor_hp', 32)->nullable();
			$table->boolean('id_jenis_tinggal')->nullable()->comment('FK: jenis_tinggal.id_jenis_tinggal');
			$table->boolean('anak_ke')->nullable();
			$table->boolean('dari_x_bersaudara')->nullable();
			$table->float('jarak_rumah_sekolah', 10, 0)->nullable()->comment('dalam km');
			$table->float('waktu_tempuh_sekolah_jam', 10, 0)->nullable()->comment('waktu tempuh dari rumah ke sekolah dalam jam');
			$table->float('waktu_tempuh_sekolah_menit', 10, 0)->nullable()->comment('waktu tempuh dari rumah ke sekolah dalam menit');
			$table->boolean('id_jenis_transportasi')->nullable()->comment('jenis_transportasi.id_jenis_transportasi');
			$table->string('nomor_kks', 32)->nullable()->comment('diisi apabila ada Kartu Keluarga Sejahtera');
			$table->boolean('is_penerima_kps')->nullable()->comment('1 = Ya; 0 = Tidak; (Kartu Perlindungan Sosial)');
			$table->string('nomor_kps', 32)->nullable()->comment('diisi apabila is_penerima_kps = 1 (Ya)');
			$table->boolean('is_punya_kip')->nullable()->comment('1 = Ya; 0 = Tidak; (Kartu Indonesia Pintar)');
			$table->string('nomor_kip', 32)->nullable()->comment('diisi apabila is_punya_kip = 1 (Ya)');
			$table->string('nm_tertera_kip', 64)->nullable()->comment('diisi apabila is_punya_kip = 1 (Ya)');
			$table->boolean('is_layak_pip')->nullable()->comment('1 = Ya; 0 = Tidak; (layak diajukan PIP)');
			$table->boolean('id_jenis_layak_pip')->nullable()->comment('FK: jenis_layak_pip.id_jenis_layak_pip (diisi apabila is_layak_pip = 1 (Ya))');
			$table->string('asal_sekolah', 128)->nullable();
			$table->string('nomor_ujian_sebelumnya', 32)->nullable();
			$table->string('nomor_ijasah_sebelumnya', 32)->nullable();
			$table->string('nomor_skhus_sebelumnya', 32)->nullable();
			$table->string('id_pilihan_jurusan_1', 40)->nullable()->comment('jurusan ke-1 yg dipilih');
			$table->string('id_pilihan_jurusan_2', 40)->nullable()->comment('jurusan ke-2 yg dipilih');
			$table->string('id_pilihan_jurusan_3', 40)->nullable()->comment('jurusan ke-3 yg dipilih');
			$table->string('nomor_ujian', 32)->nullable()->comment('nomor ujian diisi ketika proses penetapan di modul peserta role ppdb (parameter ketika akan melakukan persidangan, harus not null/sudah terisi)');
			$table->string('nis_siswa', 64)->nullable()->comment('diisi ketika sudah menjadi siswa (saat mengisi tanggal_generate_nis)');
			$table->string('id_jurusan', 40)->nullable()->comment('diisi ketika sudah diterima di persidangan (saat mengisi tanggal_diterima)');
			$table->date('tgl_registrasi')->nullable()->comment('tanggal saat calon_siswa pertama kali login ke sistem PPDB Online');
			$table->date('tgl_verifikasi_dokumen')->nullable()->comment('tanggal saat petugas melakukan verifikasi dokumen');
			$table->date('tgl_diterima')->nullable()->comment('tanggal saat diterima di persidangan (penetapan)');
			$table->date('tgl_generate_nis')->nullable()->comment('tanggal saat siswa memperoleh nis dari generate di role pendidikan');
			$table->date('tgl_cetak_kartu_pelajar')->nullable()->comment('tanggal saat siswa melakukan pengambilan kartu pelajar');
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
		Schema::drop('calon_siswa_baru');
	}

}
