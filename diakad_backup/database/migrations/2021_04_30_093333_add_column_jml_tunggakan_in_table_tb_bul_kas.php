<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnJmlTunggakanInTableTbBulKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutup_buku_bulanan_kas', function (Blueprint $table) {
            $table->float('sisa_tunggakan_biaya', 10, 0)->nullable()->after('kas_akhir_bulan');
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
