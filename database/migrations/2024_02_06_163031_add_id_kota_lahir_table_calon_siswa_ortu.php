<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdKotaLahirTableCalonSiswaOrtu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_ortu', function (Blueprint $table) {
            $table->integer('id_kota_lahir_ayah')->after('nik_ayah')->nullable();
            $table->integer('id_kota_lahir_ibu')->after('nik_ibu')->nullable();
            $table->integer('id_kota_lahir_wali')->after('nik_wali')->nullable();
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
