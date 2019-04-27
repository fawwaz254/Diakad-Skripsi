<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdSiswaEntryPresensiHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensi_harian', function (Blueprint $table) {
            $table->string('id_siswa_entry', 40)->comment('FK: siswa.id_siswa (null apabila presensi oleh guru piket)')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensi_harian', function ($table) {
            $table->dropColumn('id_siswa_entry');
        });
    }
}
