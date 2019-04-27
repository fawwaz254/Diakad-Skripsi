<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemasukanBiayaSubkategoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemasukan_biaya_subkategori', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pemasukan_biaya_subkategori', 40);
            $table->primary('id_pemasukan_biaya_subkategori', 'my_long_table_primary');
            $table->string('id_pemasukan_biaya_kategori', 40)->nullable()->comment('FK: pemasukan_biaya_kategori.id_pemasukan_biaya_kategori');
            $table->string('nm_pemasukan_biaya_subkategori', 128)->nullable();
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
        Schema::dropIfExists('pemasukan_biaya_subkategori');
    }
}
