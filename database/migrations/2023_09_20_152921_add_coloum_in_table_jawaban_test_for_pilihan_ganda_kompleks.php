<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumInTableJawabanTestForPilihanGandaKompleks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jawaban_test', function (Blueprint $table) {
            $table->string('id_pilihan_soal_kompleks5', 40)->after('id_pilihan_soal')->nullable();
            $table->string('id_pilihan_soal_kompleks4', 40)->after('id_pilihan_soal')->nullable();
            $table->string('id_pilihan_soal_kompleks3', 40)->after('id_pilihan_soal')->nullable();
            $table->string('id_pilihan_soal_kompleks2', 40)->after('id_pilihan_soal')->nullable();
            $table->string('id_pilihan_soal_kompleks1', 40)->after('id_pilihan_soal')->nullable();
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
