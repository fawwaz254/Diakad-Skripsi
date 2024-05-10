<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnInPredikatRaporPendukung extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('predikat_rapor_pendukung', function (Blueprint $table) {
            $table->string('id_rapor_pendukung', 40)->nullable()->comment('FK:rapor_pendukung.id_rapor_pendukung')->after('id_indikator_rapor_pendukung');
            $table->string('tipe', 40)->nullable()->comment('PREDIKAT: nilai siswa || CATATAN: catatan siswa (opsional)')->after('id_siswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('predikat_rapor_pendukung', function (Blueprint $table) {
            //
        });
    }
}
