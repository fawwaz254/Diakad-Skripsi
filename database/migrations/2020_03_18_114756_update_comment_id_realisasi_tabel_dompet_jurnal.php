<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCommentIdRealisasiTabelDompetJurnal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dompet_jurnal', function (Blueprint $table) {
            $table->string('id_realisasi', 40)->nullable()->after('id_dompet')->comment('FK: realisasi.id_realisasi, jika null berarti saldo awal di bulan tersebut atau dari transfer dompet')->change();
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
