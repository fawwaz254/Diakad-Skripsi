<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIsAktifTableKelompokBiayaInternal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelompok_biaya_internal', function (Blueprint $table) {
            $table->tinyInteger('is_aktif')->default('1')->after('nm_kelompok_biaya_internal')->comment('1 = Aktif; 0 = Non-Aktif;');
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
