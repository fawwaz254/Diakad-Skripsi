<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKategoriRapbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_rapb', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kategori_rapb', 40)->primary();
            $table->string('kode_kategori_rapb', 128)->nullable();
            $table->string('nm_kategori_rapb', 512)->nullable();
            $table->string('deskripsi_kategori_rapb', 1024)->nullable();
            $table->boolean('tipe_kategori_rapb')->nullable()->comment('1 = Penerimaan; 2 = Pengeluaran;');
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
        Schema::dropIfExists('kategori_rapb');
    }
}
