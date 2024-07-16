<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRppDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapel_rpp_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id_mapel_rpp_detail');
            $table->integer('id_mapel_rpp');
            $table->integer('pertemuan_ke')->nullable();
            $table->longText('deskripsi');
            $table->longText('model_pembelajaran');
            $table->longText('nilai_karakter');
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mapel_rpp_detail');
    }
}
