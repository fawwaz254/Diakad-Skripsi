<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingKodeKelompokDanUnitKerjaTabelKelompokDanInventarisRuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelompok_inventaris', function (Blueprint $table) {
            $table->string('kode_kelompok_inventaris', 128)->after('id_sekolah')->nullable();
        });

        Schema::table('inventaris_ruangan', function (Blueprint $table) {
            $table->string('id_unit_kerja', 40)->after('id_kelompok_inventaris')->comment('FK: unit_kerja.id_unit_kerja')->nullable();
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
