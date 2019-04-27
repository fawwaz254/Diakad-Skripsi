<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTglUjianJamMulaiJamSelesaiTableUjianMpPresensi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ujian_mp_presensi', function (Blueprint $table) {
            $table->date('tgl_ujian_mp_presensi')->after('alasan')->nullable()->comment('tgl ketika siswa login (online) atau absen (offline)');
            $table->string('jam_mulai_presensi', 5)->after('tgl_ujian_mp_presensi')->nullable()->comment('jam mulai ketika siswa login (online) atau absen (offline)');
            $table->string('jam_selesai_presensi', 5)->after('jam_mulai_presensi')->nullable()->comment('jam selesai ketika siswa login (online) atau absen (offline)');
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
