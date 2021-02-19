<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLokasiAndPenyelenggaraToKegiatanSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatan_siswa', function (Blueprint $table) {
            $table->string('lokasi_kegiatan_siswa', 64)->nullable()->after('nm_kegiatan_siswa');
            $table->string('penyelenggara_kegiatan_siswa', 128)->nullable()->after('lokasi_kegiatan_siswa');
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
