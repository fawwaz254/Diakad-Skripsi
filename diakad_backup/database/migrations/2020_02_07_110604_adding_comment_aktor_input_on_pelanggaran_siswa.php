<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingCommentAktorInputOnPelanggaranSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->boolean('aktor_input_pelanggaran')->nullable()->comment('1 = Role BK; 2 = Role Kesiswaan; 3 = Wali Kelas; (Guru Mapel ada di tabel presensi_mp_pelanggaran); 4 = Guru Biasa; 5 = Guru Piket;')->change();
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
