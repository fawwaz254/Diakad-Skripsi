<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDompetTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dompet_transfer', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_dompet_transfer', 40)->primary();
            $table->string('id_dompet_asal', 40)->comment('FK: dompet.id_dompet');
            $table->string('id_dompet_tujuan', 40)->comment('FK: dompet.id_dompet');
            $table->date('tgl_dompet_transfer')->nullable();
            $table->decimal('nominal_dompet_transfer', 10, 0)->nullable();
            $table->string('id_pengguna_kepala_keuangan', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala keuangan yg melakukan approve Realisasi');
            $table->date('tgl_apv_dompet_transfer')->nullable();
            $table->string('keterangan_dompet_transfer', 1024)->nullable();
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
        Schema::dropIfExists('dompet_transfer');
    }
}
