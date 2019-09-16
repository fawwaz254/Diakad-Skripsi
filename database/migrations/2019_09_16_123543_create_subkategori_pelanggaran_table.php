<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubkategoriPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subkategori_pelanggaran', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_subkategori_pelanggaran', 40)->primary();
            $table->string('id_kategori_pelanggaran', 40)->comment('FK: kategori_pelanggaran.id_kategori_pelanggaran');
            $table->text('nm_subkategori_pelanggaran')->nullable();
            $table->integer('tingkat_subkategori_pelanggaran')->nullable();
            $table->float('poin_subkategori_pelanggaran', 10, 0)->nullable();
            $table->string('keterangan_subkategori_pelanggaran', 512)->nullable();
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
        Schema::dropIfExists('subkategori_pelanggaran');
    }
}
