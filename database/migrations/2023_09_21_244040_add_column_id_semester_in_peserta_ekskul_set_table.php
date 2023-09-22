<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdSemesterInPesertaEkskulSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peserta_ekskul_set', function (Blueprint $table) {
            $table->string('id_semester')->nullable()->after('id_ekskul');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peserta_ekskul_set', function (Blueprint $table) {
            //
        });
    }
}
