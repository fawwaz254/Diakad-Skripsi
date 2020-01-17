<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIdKelasToNullableFalseInSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan_biaya', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('beasiswa_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('home_visit', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('pengajuan_wisuda', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('pengambilan_ekskul', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('pengambilan_magang', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('perpus_pinjaman', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('presensi_ekskul_peserta', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('prestasi_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });

        Schema::table('rapor_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi')->change();
        });
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
