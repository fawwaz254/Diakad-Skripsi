<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrutanToRaporKelompokMpAndRaporSubkelompokMp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add urutan to rapor_kelompok_mp
        Schema::table('rapor_kelompok_mp', function (Blueprint $table) {
            $table->integer('urutan_rapor_kelompok_mp')->after('nm_rapor_kelompok_mp')->nullable();
        });

        // add urutan to rapor_subkelompok_mp
        Schema::table('rapor_subkelompok_mp', function (Blueprint $table) {
            $table->integer('urutan_rapor_subkelompok_mp')->after('id_mata_pelajaran')->nullable();
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
