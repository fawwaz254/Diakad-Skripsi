<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTglSubmitFormTableCalonSiswaBaru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_baru', function (Blueprint $table) {
            $table->date('tgl_submit_form')->after('tgl_registrasi')->nullable()->comment('tgl ketika calon siswa melakukan submit form');
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
