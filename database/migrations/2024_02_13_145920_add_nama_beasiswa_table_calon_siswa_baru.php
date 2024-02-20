<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaBeasiswaTableCalonSiswaBaru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->string('nm_beasiswa_thn_1')->after('penerima_beasiswa_thn_1')->nullable();
            $table->string('nm_beasiswa_thn_2')->after('penerima_beasiswa_thn_2')->nullable();
            $table->string('nm_beasiswa_thn_3')->after('penerima_beasiswa_thn_3')->nullable();
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
