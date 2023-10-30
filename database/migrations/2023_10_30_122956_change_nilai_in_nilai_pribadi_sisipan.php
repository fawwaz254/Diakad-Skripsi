<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNilaiInNilaiPribadiSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai_pribadi_sisipan', function (Blueprint $table) {
            $table->string('nilai', 258)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('nilai_pribadi_sisipan', function (Blueprint $table) {
        //     //
        // });
    }
}
