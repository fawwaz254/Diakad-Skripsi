<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTglPembelianKodeTableInventarisBukualat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventaris_ruangan', function (Blueprint $table) {
            $table->string('kode_inventaris_ruangan', 64)->after('nm_inventaris_ruangan')->nullable()->comment('kode yg juga menempel pada barang');
            $table->date('tgl_pembelian')->after('kode_inventaris_ruangan')->nullable()->comment('tgl ketika barang dibeli');
        });

        Schema::table('buku_alat', function (Blueprint $table) {
            $table->string('kode_buku_alat', 64)->after('id_mata_pelajaran')->nullable()->comment('kode yg juga menempel pada barang');
            $table->date('tgl_pembelian')->after('kode_buku_alat')->nullable()->comment('tgl ketika barang dibeli');
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
