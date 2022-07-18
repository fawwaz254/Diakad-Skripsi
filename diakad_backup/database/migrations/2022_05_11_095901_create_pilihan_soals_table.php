<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePilihanSoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilihan_soal', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pilihan_soal', 40)->primary();
            $table->string('id_soal', 40)->nullable();
            $table->tinyInteger('number_option')->nullable();
            $table->string('content', 256)->nullable();
            $table->string('text', 256)->nullable();
            $table->tinyInteger('correct')->nullable();
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
        Schema::dropIfExists('pilihan_soal');
    }
}
