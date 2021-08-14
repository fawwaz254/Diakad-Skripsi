<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnToMateriAjar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materi_ajar', function (Blueprint $table) {
            $table->string('id_mata_pelajaran', 40)->after('id_materi_ajar')->nullable();
            $table->string('id_jurusan', 40)->after('id_mata_pelajaran')->nullable();
            $table->string('tingkat', 40)->after('id_jurusan')->nullable();
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
