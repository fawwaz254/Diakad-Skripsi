<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdSubkategoriOnPresensiMpPelanggaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('presensi_mp_pelanggaran', function (Blueprint $table) {
            $table->string('id_subkategori_pelanggaran', 40)->after('id_kelas')->comment('FK: subkategori_pelanggaran.id_subkategori_pelanggaran');
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
