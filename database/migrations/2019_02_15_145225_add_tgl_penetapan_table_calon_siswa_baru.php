<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTglPenetapanTableCalonSiswaBaru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->date('tgl_penetapan')->after('tgl_verifikasi_dokumen')->nullable()->comment('tgl ketika calon siswa melakukan ditetapkan sebelum persidangan');
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
