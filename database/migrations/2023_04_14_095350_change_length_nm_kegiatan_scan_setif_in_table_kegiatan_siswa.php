<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLengthNmKegiatanScanSetifInTableKegiatanSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatan_siswa', function (Blueprint $table) {
            $table->string('nm_kegiatan_scan_sertif', 256)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kegiatan_siswa', function (Blueprint $table) {
            //
        });
    }
}
