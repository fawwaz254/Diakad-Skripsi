<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumKkmInRaporSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rapor_sisipan', function($table) {
            $table->tinyInteger('kkm')->nullable()->after('id_mata_pelajaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('rapor_sisipan', function (Blueprint $table) {
        //     //
        // });
    }
}
