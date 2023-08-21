<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNewToCalonSiswaBaruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->string('nm_panggilan', 256)->after('nm_c_siswa')->nullable();
            $table->string('alamat_asal_sekolah', 256)->after('asal_sekolah')->nullable();
            $table->string('nomor_sttb', 256)->after('alamat_asal_sekolah')->nullable();
            $table->date('tanggal_sttb')->after('nomor_sttb')->nullable();
            $table->date('tanggal_skhus_sebelumnya')->after('nomor_skhus_sebelumnya')->nullable();
            $table->string('kegemaran_kesenian', 256)->after('nis_siswa')->nullable();
            $table->string('kegemaran_olahraga', 256)->after('kegemaran_kesenian')->nullable();
            $table->string('kegemaran_organisasi', 256)->after('kegemaran_olahraga')->nullable();
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
            $table->dropColumn('nm_panggilan');
            $table->dropColumn('alamat_asal_sekolah');
            $table->dropColumn('nomor_sttb');
            $table->dropColumn('tanggal_sttb');
            $table->dropColumn('tanggal_skhus_sebelumnya');
            $table->dropColumn('kegemaran_kesenian');
            $table->dropColumn('kegemaran_olahraga');
            $table->dropColumn('kegemaran_organisasi');
        });
    }
}
