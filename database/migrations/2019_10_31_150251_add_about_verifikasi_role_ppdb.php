<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAboutVerifikasiRolePpdb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->date('tgl_submit_verifikasi')->after('tgl_submit_form')->nullable()->comment('tgl saat calon siswa melakukan submit verifikasi');
            $table->date('tgl_proses_verifikasi')->after('tgl_submit_verifikasi')->nullable()->comment('tgl saat petugas mulai mengerjakan proses verifikasi');
            $table->boolean('status_verifikasi')->after('tgl_verifikasi_dokumen')->nullable()->comment('0-Default; 1-Diterima; 2-Sdh Submit; 3-Perbaikan; 4-Ditolak');
            $table->string('id_pengguna_verifikator', 40)->after('status_verifikasi')->nullable()->comment('FK: pengguna.id_pengguna');

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
