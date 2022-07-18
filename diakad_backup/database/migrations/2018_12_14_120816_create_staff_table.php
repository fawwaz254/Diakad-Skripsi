<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_staff', 40)->primary();
			$table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
			$table->string('id_unit_kerja', 40)->comment('FK: unit_kerja.id_unit_kerja');
			$table->string('nip_staff', 32)->nullable()->comment('sama dengan niy_nigk_ptk utk sekolah swasta atau nip_ptk utk PNS');
			$table->string('nik_ptk', 64)->nullable()->comment('nomor induk kependudukan yg ada di KK atau KTP');
			$table->boolean('jenis_kelamin')->nullable()->comment('1 = Laki-laki; 2 = Perempuan;');
			$table->integer('id_kota_lahir')->nullable()->comment('FK: kota.id_kota');
			$table->date('tgl_lahir')->nullable();
			$table->string('nm_ibu_kandung', 64)->nullable();
			$table->string('alamat_jalan', 128)->nullable();
			$table->string('alamat_rt', 4)->nullable();
			$table->string('alamat_rw', 4)->nullable();
			$table->string('alamat_dusun', 64)->nullable();
			$table->string('alamat_kelurahan', 64)->nullable();
			$table->string('alamat_kecamatan', 64)->nullable();
			$table->string('alamat_kodepos', 8)->nullable();
			$table->integer('alamat_kota')->nullable()->comment('FK: kota.id_kota');
			$table->boolean('alamat_provinsi')->nullable()->comment('FK: provinsi.id_provinsi');
			$table->string('alamat_latitude', 128)->nullable()->comment('posisi geografis garis lintang (diisi dari hasil pinned di maps)');
			$table->string('alamat_longitude', 128)->nullable()->comment('posisi geografis garis bujur (diisi dari hasil pinned di maps)');
			$table->boolean('id_agama')->nullable();
			$table->string('npwp_ptk', 64)->nullable();
			$table->string('nm_wajib_pajak_ptk', 64)->nullable();
			$table->boolean('kewarganegaraan')->nullable()->comment('1 = WNI; 2 = WNA;');
			$table->boolean('status_kawin')->nullable()->comment('1 = kawin; 2 = belum kawin; 3 = janda/duda;');
			$table->string('nm_pasangan_ptk', 64)->nullable()->comment('nama suami/istri (diisi apabila sudah menikah)');
			$table->string('nip_pasangan_ptk', 64)->nullable()->comment('nip suami/istri apabila PNS');
			$table->boolean('id_jenis_pekerjaan_pasangan_ptk')->nullable()->comment('FK: jenis_pekerjaan.id_jenis_pekerjaan');
			$table->boolean('id_jenis_kepegawaian')->nullable()->comment('FK: jenis_kepegawaian.id_jenis_kepegawaian');
			$table->string('nip_ptk', 64)->nullable()->comment('diisi untuk PNS saja');
			$table->string('niy_nigk_ptk', 64)->nullable()->comment('nomor induk yayasan / nomor induk guru kontrak (untuk sekolah swasta) (ini sama dengan nip_guru)');
			$table->string('nuptk', 64)->nullable()->comment('jika ada');
			$table->boolean('id_jenis_ptk')->nullable()->comment('FK: jenis_ptk.id_jenis_ptk (sama dengan jabatan pegawai)');
			$table->string('nomor_sk_pengangkatan', 64)->nullable()->comment('diisi sesuai id_jenis_kepegawaian');
			$table->date('tgl_sk_pengangkatan')->nullable()->comment('tgl sk pengangkatan sesuai nomor sk pengangkatan');
			$table->boolean('id_jenis_lembaga_pengangkat')->nullable()->comment('FK: jenis_lembaga_pengangkat.id_jenis_lembaga_pengangkat');
			$table->string('nomor_sk_cpns', 64)->nullable()->comment('diisi apabila ptk adalah cpns');
			$table->date('tgl_mulai_pns')->nullable()->comment('diisi apabila ptk adalah PNS');
			$table->string('golongan_ptk', 8)->nullable()->comment('diisi pangkat/golongan terbaru dari ptk');
			$table->boolean('id_jenis_sumber_gaji')->nullable()->comment('FK: jenis_sumber_gaji.id_jenis_sumber_gaji');
			$table->string('nomor_kartu_pegawai', 64)->nullable()->comment('KARPEG diisi apabila ptk adalan PNS');
			$table->string('nomor_kartu_pasangan', 64)->nullable()->comment('diisi apabila ptk adalah PNS (diisi nomor kartu istri/suami)');
			$table->boolean('is_lisensi_kepsek')->nullable()->comment('1 = punya lisensi kepsek; 0 = tidak punya lisensi kepsek;');
			$table->boolean('id_jenis_keahlian_lab')->nullable()->comment('FK: jenis_keahlian_lab.id_jenis_keahlian_lab');
			$table->boolean('is_keahlian_braile')->nullable()->comment('1 = Ya; 0 = Tidak;');
			$table->boolean('is_keahlian_bahasa_isyarat')->nullable()->comment('1 = Ya; 0 = Tidak;');
			$table->string('nomor_telp', 32)->nullable()->comment('nomor telepon rumah');
			$table->string('nomor_hp', 32)->nullable();
			$table->string('email', 64)->nullable()->comment('email pribadi ptk');
			$table->string('nomor_sk_penugasan', 64)->nullable()->comment('Nomor SK penugasan PTK. Bagi PTK di sekolah induk, diisi nomor SK penugasan/penempatan pertama kali di sekolah ini. Bagi PTK dengan status sekolah bukan induk, diisi nomor SK pengangkatan atau pembagian tugas mengajar (bagi guru) yang terbit setiap tahun. Bagi PTK WNA, diisi dengan Rekomendasi Izin Mempekerjakan Tenaga Asing (IMTA) dari Kemdikbud');
			$table->date('tgl_sk_penugasan')->nullable();
			$table->date('tgl_mulai_penugasan')->nullable();
			$table->boolean('is_sekolah_induk')->nullable()->comment('1 = Ya; 0 = Tidak; (Penugasan PTK di sekolah ini sebagai induk atau bukan)');
			$table->string('alasan_keluar', 128)->nullable()->comment('diisi ketika ptk keluar (id_status_pengguna keluar)');
			$table->date('tgl_keluar')->nullable()->comment('diisi ketika ptk keluar (id_status_pengguna keluar)');
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
		Schema::drop('staff');
	}

}
