<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeteranganTablePemasukanBiayaKategoriDanSubkategori extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemasukan_biaya_kategori', function (Blueprint $table) {
            $table->string('keterangan_pemasukan_biaya_kategori', 512)->after('nm_pemasukan_biaya_kategori')->nullable();
        });

        Schema::table('pemasukan_biaya_subkategori', function (Blueprint $table) {
            $table->string('keterangan_pemasukan_biaya_subkategori', 512)->after('nm_pemasukan_biaya_subkategori')->nullable();
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
