<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdKelasTablePresensiMpPelanggaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensi_mp_pelanggaran', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->nullable()->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan pelanggaran');
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
