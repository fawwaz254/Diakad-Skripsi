<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePribadiSisipansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pribadi_sisipan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pribadi_sisipan', 40)->primary();
            $table->string('id_kelompok_pribadi_sisipan', 40);
            $table->string('nm_pribadi_sisipan', 128);
            $table->integer('urutan');
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
        // Schema::dropIfExists('pribadi_sisipans');
    }
}
