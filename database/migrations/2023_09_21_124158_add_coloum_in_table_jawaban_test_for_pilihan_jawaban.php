<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumInTableJawabanTestForPilihanJawaban extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jawaban_test', function (Blueprint $table) {
            $table->string('pilihan_jawaban5', 40)->after('jawaban_essay')->nullable();
            $table->string('pilihan_jawaban4', 40)->after('jawaban_essay')->nullable();
            $table->string('pilihan_jawaban3', 40)->after('jawaban_essay')->nullable();
            $table->string('pilihan_jawaban2', 40)->after('jawaban_essay')->nullable();
            $table->string('pilihan_jawaban1', 40)->after('jawaban_essay')->nullable();
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
