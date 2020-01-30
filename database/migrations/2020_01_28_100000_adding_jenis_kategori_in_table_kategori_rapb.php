<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingJenisKategoriInTableKategoriRapb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kategori_rapb', function (Blueprint $table) {
            $table->tinyInteger('jenis_kategori_rapb')->default('0')->after('tipe_kategori_rapb')->comment('0 = Non-SPP; 1 = SPP;');
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
