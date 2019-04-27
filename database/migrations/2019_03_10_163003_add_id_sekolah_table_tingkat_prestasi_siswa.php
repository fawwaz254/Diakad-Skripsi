<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdSekolahTableTingkatPrestasiSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tingkat_prestasi_siswa', function (Blueprint $table) {
            $table->string('id_sekolah', 40)->after('nm_tingkat_prestasi_siswa')->comment('FK: sekolah.id_sekolah');
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
