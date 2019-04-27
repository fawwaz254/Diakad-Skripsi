<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeteranganTableKategoriDanSubkategoriPengeluaranBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengeluaran_biaya_kategori', function (Blueprint $table) {
            $table->string('keterangan_pengeluaran_biaya_kategori', 512)->after('nm_pengeluaran_biaya_kategori')->nullable();
        });

        Schema::table('pengeluaran_biaya_subkategori', function (Blueprint $table) {
            $table->string('keterangan_pengeluaran_biaya_subkategori', 512)->after('nm_pengeluaran_biaya_subkategori')->nullable();
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
