<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBiayaDaftarUlangTablePenerimaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            $table->decimal('biaya_daftar_ulang', 10, 2)->after('nomor_rekening_transfer')->nullable()->comment('biaya daftar ulang sebelum fix menjadi siswa');
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
