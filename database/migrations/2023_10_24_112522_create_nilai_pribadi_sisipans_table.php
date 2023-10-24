<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiPribadiSisipansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_pribadi_sisipan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_nilai_pribadi_sisipan', 40)->primary();
            $table->string('id_pribadi_sisipan', 40);
            $table->string('id_siswa', 40);
            $table->string('id_semester', 40);
            $table->string('nilai', 128);
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
        // Schema::dropIfExists('nilai_pribadi_sisipans');
    }
}
