<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHargaTabelInventarisHabisPakai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventaris_habis_pakai', function (Blueprint $table) {
            $table->decimal('harga_inventaris_habis_pakai', 10, 0)->nullable()->after('qty_inventaris_habis_pakai')->comment('harga masuk rpb_sarpras_habis_pakai berbeda dengan harga lama pakai rumus : ( ((stok lama * harga lama) + (stok baru * harga baru)) / (stok lama + stok baru) )')->change();
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
