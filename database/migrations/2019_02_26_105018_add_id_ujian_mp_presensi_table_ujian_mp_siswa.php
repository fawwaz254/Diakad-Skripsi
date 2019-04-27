<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdUjianMpPresensiTableUjianMpSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ujian_mp_siswa', function (Blueprint $table) {
            $table->string('id_ujian_mp_presensi', 40)->after('id_siswa')->comment('FK: ujian_mp_presensi.id_ujian_mp_presensi');
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
