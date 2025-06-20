<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventarisHabisPakaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaris_habis_pakai', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_inventaris_habis_pakai', 40)->primary();
            $table->string('id_satuan', 40)->comment('FK: satuan.id_satuan');
            $table->string('id_unit_kerja', 40)->nullable()->comment('FK: unit_kerja.id_unit_kerja, jika null berarti stok di gudang sarpras');
            $table->string('kode_inventaris_habis_pakai', 128)->nullable();
            $table->string('nm_inventaris_habis_pakai', 512)->nullable();
            $table->date('tgl_masuk_terakhir')->nullable();
            $table->integer('qty_inventaris_habis_pakai')->nullable();
            $table->decimal('harga_inventaris_habis_pakai', 10, 0)->nullable();
            $table->decimal('jumlah_inventaris_habis_pakai', 10, 0)->nullable()->comment('qty * harga');
            $table->string('keterangan_inventaris_habis_pakai', 1024)->nullable();
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
        Schema::dropIfExists('inventaris_habis_pakai');
    }
}
