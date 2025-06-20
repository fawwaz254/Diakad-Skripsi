<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDompetJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dompet_jurnal', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_dompet_jurnal', 40)->primary();
            $table->string('id_dompet', 40)->comment('FK: dompet.id_dompet');
            $table->string('id_realisasi', 40)->comment('FK: realisasi.id_realisasi, jika null berarti saldo awal di bulan tersebut')->nullable();
            $table->date('tgl_dompet_jurnal')->nullable();
            $table->decimal('debit', 10, 0)->default(0)->nullable();
            $table->decimal('kredit', 10, 0)->default(0)->nullable();
            $table->decimal('saldo_akhir', 10, 0)->nullable();
            $table->string('keterangan_dompet_jurnal', 1024)->nullable();
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
        Schema::dropIfExists('dompet_jurnal');
    }
}
