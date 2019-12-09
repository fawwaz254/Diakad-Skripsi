<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIdKelasOnSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan_biaya', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('beasiswa_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('home_visit', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('pengajuan_wisuda', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('pengambilan_ekskul', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('pengambilan_magang', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('perpus_pinjaman', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('presensi_ekskul_peserta', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('prestasi_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        Schema::table('rapor_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
        });

        DB::update('UPDATE tagihan_biaya SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = tagihan_biaya.id_siswa)');
        DB::update('UPDATE beasiswa_siswa SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = beasiswa_siswa.id_siswa)');
        DB::update('UPDATE home_visit  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = home_visit.id_siswa)');
        DB::update('UPDATE pengajuan_wisuda  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = pengajuan_wisuda.id_siswa)');
        DB::update('UPDATE pengambilan_ekskul  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = pengambilan_ekskul.id_siswa)');
        DB::update('UPDATE pengambilan_magang  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = pengambilan_magang.id_siswa)');
        DB::update('UPDATE perpus_pinjaman  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = perpus_pinjaman.id_siswa)');
        DB::update('UPDATE presensi_ekskul_peserta  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = presensi_ekskul_peserta.id_siswa)');
        DB::update('UPDATE prestasi_siswa  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = prestasi_siswa.id_siswa)');
        DB::update('UPDATE rapor_siswa  SET id_kelas = (SELECT id_kelas FROM siswa WHERE siswa.id_siswa = rapor_siswa.id_siswa)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
