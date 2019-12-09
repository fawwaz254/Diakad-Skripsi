<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealisasiPakPembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realisasi_pak_pembayaran', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_realisasi_pak_pembayaran', 40)->primary();
            $table->string('id_realisasi_pak', 40)->comment('FK: realisasi_pak.id_realisasi_pak');
            $table->boolean('termin_ke')->nullable();
            $table->date('tgl_pembayaran')->nullable();
            $table->float('dana_realisasi_pembayaran', 10, 0)->nullable();
            $table->string('id_pengguna_kepala_keuangan', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala keuangan yg melakukan approve Realisasi PAK Pembayaran');
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
        Schema::dropIfExists('realisasi_pak_pembayaran');
    }
}
