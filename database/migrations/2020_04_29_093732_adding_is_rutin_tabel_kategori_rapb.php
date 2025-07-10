<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIsRutinTabelKategoriRapb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kategori_rapb', function (Blueprint $table) {
            $table->integer('is_rutin')->default('1')->after('jenis_kategori_rapb')->comment('1 = Rutin; 2 = Non-Rutin;')->nullable();
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
