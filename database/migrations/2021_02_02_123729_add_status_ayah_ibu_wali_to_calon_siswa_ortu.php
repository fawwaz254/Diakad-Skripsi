<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAyahIbuWaliToCalonSiswaOrtu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_ortu', function (Blueprint $table) {
            $table->integer('status_ayah')->after('nm_ayah')->nullable();
            $table->integer('status_ibu')->after('nm_ibu')->nullable();
            $table->integer('status_wali')->after('nm_wali')->nullable();
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
             $table->dropColumn('status_ayah');
             $table->dropColumn('status_ibu');
             $table->dropColumn('status_wali');
        });
    }
}
