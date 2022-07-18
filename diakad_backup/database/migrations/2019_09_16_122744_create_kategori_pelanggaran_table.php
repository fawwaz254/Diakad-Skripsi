<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKategoriPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_pelanggaran', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kategori_pelanggaran', 40)->primary();
            $table->string('nm_kategori_pelanggaran', 128)->nullable();
            $table->integer('tingkat_kategori_pelanggaran')->nullable();
            $table->string('keterangan_kategori_pelanggaran', 256)->nullable();
            $table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
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
        Schema::dropIfExists('kategori_pelanggaran');
    }
}
