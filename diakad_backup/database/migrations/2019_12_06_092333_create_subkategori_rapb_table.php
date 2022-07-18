<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubkategoriRapbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subkategori_rapb', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_subkategori_rapb', 40)->primary();
            $table->string('id_kategori_rapb', 40)->comment('FK: kategori_rapb.id_kategori_rapb');
            $table->string('kode_subkategori_rapb', 128)->nullable();
            $table->string('nm_subkategori_rapb', 512)->nullable();
            $table->string('deskripsi_subkategori_rapb', 1024)->nullable();
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
        Schema::dropIfExists('subkategori_rapb');
    }
}
