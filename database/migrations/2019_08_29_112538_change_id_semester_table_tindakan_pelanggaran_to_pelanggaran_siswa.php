<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdSemesterTableTindakanPelanggaranToPelanggaranSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tindakan_pelanggaran', function (Blueprint $table) {
            $table->dropColumn('id_semester');
        });

        Schema::table('pelanggaran_siswa', function (Blueprint $table) {
            $table->string('id_semester', 40)->after('id_guru_input')->comment('FK: semester.id_semester');
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
