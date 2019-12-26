<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIsAktifWaliMuridInTableSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('siswa', function (Blueprint $table) {
            $table->tinyInteger('is_aktif_wali_murid')->default('1')->after('id_wali_murid')->comment('Field ini digunakan untuk menandai apabila terdapat wali murid yang memiliki anak murid lebih dari 1');
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
