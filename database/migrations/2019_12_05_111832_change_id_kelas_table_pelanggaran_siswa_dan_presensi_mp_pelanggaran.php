<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdKelasTablePelanggaranSiswaDanPresensiMpPelanggaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan pelanggaran')->change();
        });

        Schema::table('presensi_mp_pelanggaran', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable(false)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan pelanggaran')->change();
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
