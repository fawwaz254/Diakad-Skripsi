<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilihanPertanyaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilihan_pertanyaan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pilihan_pertanyaan', 40)->primary();
            $table->string('id_soal', 40);
            $table->string('nomer', 40);
            $table->string('text', 256);
            $table->integer('jawaban');
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
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
