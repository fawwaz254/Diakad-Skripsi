<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLengthColumnPilihanPertanyaanPilihanJawaban extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pilihan_pertanyaan', function (Blueprint $table) {
            $table->text('text', 65535)->change();
        });

        Schema::table('pilihan_jawaban', function (Blueprint $table) {
            $table->text('text', 65535)->change();
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
