<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdKelasTablePelanggaranSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->string('id_kelas', 40)->after('id_siswa')->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan pelanggaran')->change();
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
