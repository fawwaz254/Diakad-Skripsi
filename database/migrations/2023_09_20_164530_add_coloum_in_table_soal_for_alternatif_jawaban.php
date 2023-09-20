<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumInTableSoalForAlternatifJawaban extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->string('alternatif_jawaban5', 40)->after('jawaban')->nullable();
            $table->string('alternatif_jawaban4', 40)->after('jawaban')->nullable();
            $table->string('alternatif_jawaban3', 40)->after('jawaban')->nullable();
            $table->string('alternatif_jawaban2', 40)->after('jawaban')->nullable();
            $table->string('alternatif_jawaban1', 40)->after('jawaban')->nullable();
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
