<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkSertifPrestasiSiswaToPrestasiSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestasi_siswa', function (Blueprint $table) {
            $table->string('link_sertif_prestasi_siswa', 128)->nullable()->after('tgl_prestasi_siswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestasi_siswa', function (Blueprint $table) {
            //
        });
    }
}
