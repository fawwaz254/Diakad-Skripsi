<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutupBukuBulananDompetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutup_buku_bulanan_dompet', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_tutup_buku_bulanan_dompet', 40)->primary();
            $table->string('id_dompet', 40)->comment('FK: dompet.id_dompet');
            $table->boolean('id_bulan')->comment('1 = Januari, 2 = Februari, dst');
            $table->integer('tahun')->comment('2019, 2020, 2021, dst');
            $table->float('saldo_akhir_dompet', 10, 0)->nullable();
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
        Schema::dropIfExists('tutup_buku_bulanan_dompet');
    }
}
