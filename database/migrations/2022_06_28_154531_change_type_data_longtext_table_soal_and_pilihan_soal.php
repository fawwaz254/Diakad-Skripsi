<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeDataLongtextTableSoalAndPilihanSoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('soal', function (Blueprint $table) {
            $table->longText('content')->change();
        });
    
        Schema::table('pilihan_soal', function (Blueprint $table) {
            $table->longText('content')->change();
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
