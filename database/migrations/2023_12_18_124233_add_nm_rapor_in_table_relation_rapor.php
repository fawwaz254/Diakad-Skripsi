<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNmRaporInTableRelationRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rapor', function (Blueprint $table) {
            $table->string('nm_rapor', 40)->after('keterangan2')->nullable();
        });

        Schema::table('kelompok_mapel_rapor', function (Blueprint $table) {
            $table->string('nm_rapor', 40)->after('urutan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
