<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaporSisipanDeskripsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapor_sisipan_deskripsi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rapor_sisipan_deskripsi', 40)->primary();
            $table->tinyInteger('tingkat');
            $table->tinyInteger('kd_deskripsi');
            $table->string('nm_mata_pelajaran', 40);
            $table->string('deskripsi1', 256);
            $table->string('deskripsi2', 256);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('rapor_sisipan_deskripsis');
    }
}
