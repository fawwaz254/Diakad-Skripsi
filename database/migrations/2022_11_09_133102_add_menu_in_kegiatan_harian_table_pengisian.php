<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuInKegiatanHarianTablePengisian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('pengisian_kegiatan_harian', function ($table) {
            $table->string('id_kegiatan_harian')->nullable()->after('id_pengguna_pengisi');
        });
        // Schema::table('kegiatan_harian_table_pengisian', function (Blueprint $table) {
        //     //
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('kegiatan_harian_table_pengisian', function (Blueprint $table) {
        //     //
        // });
    }
}
