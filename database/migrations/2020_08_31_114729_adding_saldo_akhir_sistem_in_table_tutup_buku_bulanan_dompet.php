<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingSaldoAkhirSistemInTableTutupBukuBulananDompet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutup_buku_bulanan_dompet', function (Blueprint $table) {
            $table->float('saldo_akhir_sistem', 10, 0)->nullable()->after('saldo_akhir_dompet');
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
