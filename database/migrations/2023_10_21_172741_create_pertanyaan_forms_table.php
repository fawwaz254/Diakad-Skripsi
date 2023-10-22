<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertanyaanFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pertanyaan_form', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pertanyaan_form', 40)->primary();
            $table->string('id_form', 40);
            $table->string('nm_pertanyaan_form', 128);
            $table->integer('jenis_pertanyaan');
            $table->integer('urutan');
            $table->json('options')->nullable();
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
