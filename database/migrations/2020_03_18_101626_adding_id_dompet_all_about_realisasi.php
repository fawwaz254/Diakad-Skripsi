<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIdDompetAllAboutRealisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realisasi', function (Blueprint $table) {
            $table->string('id_dompet', 40)->nullable()->after('id_unit_kerja')->comment('FK: dompet.id_dompet, Wajib diisi ketika cek staf Keuangan');
        });

        Schema::table('realisasi_pak', function (Blueprint $table) {
            $table->string('id_dompet', 40)->nullable()->after('id_unit_kerja')->comment('FK: dompet.id_dompet, Wajib diisi ketika cek staf Keuangan');
        });

        Schema::table('realisasi_warehouses', function (Blueprint $table) {
            $table->string('transaction_method', 40)->nullable()->after('transaction_nominal')->comment('Tunai or Bank');
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
