<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColoumInTablePointKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::table('point_kpi', function (Blueprint $table) {
        DB::statement('ALTER TABLE point_kpi ALTER COLUMN deskripsi TYPE json USING deskripsi::json');
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
