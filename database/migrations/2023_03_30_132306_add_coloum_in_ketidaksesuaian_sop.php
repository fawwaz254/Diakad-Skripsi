<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumInKetidaksesuaianSop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ketidaksesuaian_sop', function (Blueprint $table) {
            $table->string('path_file', 40)->after('tgl_pelanggaran')->nullable();
            $table->string('nm_file', 40)->after('tgl_pelanggaran')->nullable();
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
