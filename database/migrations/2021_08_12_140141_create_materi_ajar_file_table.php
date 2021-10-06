<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMateriAjarFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materi_ajar_file', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_materi_ajar_file', 40)->primary();
            $table->string('id_materi_ajar', 40);
            $table->string('nm_file')->nullable();
            $table->string('type_file')->nullable();
            $table->string('link_file')->nullable();
            $table->integer('views')->nullable();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->string('deleted_by', 40)->nullable();
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
        Schema::dropIfExists('materi_ajar_file');
    }
}
