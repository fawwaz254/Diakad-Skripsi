<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumSekolahMutasiInTableCalonSiswaSekolah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_sekolah', function (Blueprint $table) {
            $table->string('nm_sekolah_mutasi', 40)->after('nm_sekolah_asal2')->nullable();
            $table->string('link_google_drive', 256)->after('nisn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
