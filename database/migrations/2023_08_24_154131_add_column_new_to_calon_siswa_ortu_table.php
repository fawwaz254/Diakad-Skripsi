<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNewToCalonSiswaOrtuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_ortu', function (Blueprint $table) {
            $table->string('hub_wali', 256)->after('status_wali')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calon_siswa_ortu', function (Blueprint $table) {
            $table->dropColumn('hub_wali');
        });
    }
}
