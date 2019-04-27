<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNmSingkatDanUrlSekolahTableSekolah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->string('nm_singkat_sekolah', 32)->after('prefix')->nullable()->comment('singkatan nama sekolah utk kebutuhan path foto, dll');
            $table->string('url_sekolah', 64)->after('nm_singkat_sekolah')->nullable()->comment('url web sekolah');
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
