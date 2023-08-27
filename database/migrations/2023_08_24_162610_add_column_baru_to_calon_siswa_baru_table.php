<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBaruToCalonSiswaBaruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->string('alamat_asal_sekolah2', 256)->after('asal_sekolah2')->nullable();
            $table->string('alasan_mutasi', 256)->after('alamat_asal_sekolah2')->nullable();
            $table->date('tanggal_mutasi_masuk')->after('alasan_mutasi')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->dropColumn('alamat_asal_sekolah2');
            $table->dropColumn('alasan_mutasi');
            $table->dropColumn('tanggal_mutasi_masuk');
            //
        });
    }
}
