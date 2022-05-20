<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJawabanTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawaban_test', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_jawaban_test', 40)->primary();
            $table->string('id_test', 40)->nullable();
            $table->string('id_pengguna', 40)->nullable();
            $table->string('nomer', 40)->nullable();
            $table->string('id_soal', 40)->nullable();
            $table->string('id_pilihan_soal', 40)->nullable();
            $table->string('correct', 40)->nullable();
            $table->integer('nilai')->nullable();
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
    {
        Schema::dropIfExists('jawaban_test');
    }
}
