<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemasukanBiayaKategoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemasukan_biaya_kategori', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pemasukan_biaya_kategori', 40)->primary();
            $table->string('nm_pemasukan_biaya_kategori', 128)->nullable();
            $table->string('id_sekolah', 40)->nullable()->comment('FK: sekolah.id_sekolah');
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
        Schema::dropIfExists('pemasukan_biaya_kategori');
    }
}
