<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumInTableKelompokKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelompok_kpi', function (Blueprint $table) {
            $table->string('nm_header_table_deskripsi')->nullable()->after('nm_kelompok_kpi');
        });
        Schema::table('kelompok_kpi', function (Blueprint $table) {
            $table->string('nm_header_table_point')->nullable()->after('nm_kelompok_kpi');
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
