<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumInTableLaporanKerjaHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_kerja_harian', function($table) {
            $table->tinyInteger('kesesuaian_program_98')->nullable()->after('uraian_kegiatan');
            $table->string('hasil')->nullable()->after('uraian_kegiatan');
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
