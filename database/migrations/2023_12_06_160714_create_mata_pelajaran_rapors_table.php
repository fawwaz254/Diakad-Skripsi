<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataPelajaranRaporsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mata_pelajaran_rapor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_mata_pelajaran_rapor', 40)->primary();
            $table->string('id_kelompok_mapel_rapor', 40)->nullable();
            $table->string('id_sub_kelompok_mapel_rapor', 40)->nullable();
            $table->string('id_mata_pelajaran', 40)->nullable();
            $table->integer('urutan');
            $table->integer('jenis')->nullable();
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
        // Schema::dropIfExists('mata_pelajaran_rapors');
    }
}
