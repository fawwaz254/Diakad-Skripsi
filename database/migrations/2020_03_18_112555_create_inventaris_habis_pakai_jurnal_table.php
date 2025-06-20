<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventarisHabisPakaiJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaris_habis_pakai_jurnal', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_inventaris_jurnal', 40)->primary();
            $table->string('id_inventaris_habis_pakai', 40)->comment('FK: inventaris_habis_pakai.id_inventaris_habis_pakai, jika id_rpb null keduanya berarti saldo awal di bulan tersebut');
            $table->string('id_rpb_sarpras_habis_pakai', 40)->comment('FK: rpb_sarpras_habis_pakai.id_rpb_sarpras_habis_pakai, jika terisi berarti debit dari pengadaan')->nullable();
            $table->string('id_rpb_unit_habis_pakai', 40)->comment('FK: rpb_unit_habis_pakai.id_rpb_unit_habis_pakai, jika terisi berarti kredit dari permintaan unit')->nullable();
            $table->date('tgl_inventaris_habis_pakai_jurnal')->nullable();
            $table->integer('debit_qty')->default(0)->nullable();
            $table->decimal('debit_harga', 10, 0)->default(0)->nullable();
            $table->decimal('debit_jumlah', 10, 0)->default(0)->comment('qty * harga')->nullable();
            $table->integer('kredit_qty')->default(0)->nullable();
            $table->decimal('kredit_harga', 10, 0)->default(0)->nullable();
            $table->decimal('kredit_jumlah', 10, 0)->default(0)->comment('qty * harga')->nullable();
            $table->integer('saldo_akhir_qty')->default(0)->nullable();
            $table->decimal('saldo_akhir_harga', 10, 0)->default(0)->nullable();
            $table->decimal('saldo_akhir_jumlah', 10, 0)->default(0)->comment('qty * harga')->nullable();
            $table->string('keterangan_inventaris_habis_pakai_jurnal', 1024)->nullable();
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
        Schema::dropIfExists('inventaris_habis_pakai_jurnal');
    }
}
