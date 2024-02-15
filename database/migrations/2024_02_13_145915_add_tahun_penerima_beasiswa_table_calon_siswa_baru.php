<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTahunPenerimaBeasiswaTableCalonSiswaBaru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->tinyInteger('penerima_beasiswa_thn_1')->after('tgl_cetak_kartu_pelajar')->nullable();
            $table->tinyInteger('penerima_beasiswa_thn_2')->after('penerima_beasiswa_thn_1')->nullable();
            $table->tinyInteger('penerima_beasiswa_thn_3')->after('penerima_beasiswa_thn_2')->nullable();
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
