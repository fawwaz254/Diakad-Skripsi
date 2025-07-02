<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKewarganegaraanTableCalonSiswaOrtu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_ortu', function (Blueprint $table) {
            $table->integer('kewarganegaraan_ayah')->after('alamat_jalan_ayah')->nullable();
            $table->string('nm_kewarganegaraan_ayah')->after('kewarganegaraan_ayah')->nullable();
            $table->integer('kewarganegaraan_ibu')->after('alamat_jalan_ibu')->nullable();
            $table->string('nm_kewarganegaraan_ibu')->after('kewarganegaraan_ibu')->nullable();
            $table->integer('kewarganegaraan_wali')->after('tgl_lahir_wali')->nullable();
            $table->string('nm_kewarganegaraan_wali')->after('kewarganegaraan_wali')->nullable();
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
