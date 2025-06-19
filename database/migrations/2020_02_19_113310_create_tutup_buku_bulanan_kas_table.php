<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutupBukuBulananKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutup_buku_bulanan_kas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_tutup_buku_bulanan_kas', 40)->primary();
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->boolean('id_bulan')->comment('1 = Januari, 2 = Februari, dst');
            $table->decimal('kas_spp', 10, 0)->nullable();
            $table->decimal('kas_rapb_penerimaan', 10, 0)->nullable();
            $table->decimal('kas_rapb_pengeluaran', 10, 0)->nullable();
            $table->decimal('kas_akhir_bulan', 10, 0)->nullable();
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
        Schema::dropIfExists('tutup_buku_bulanan_kas');
    }
}
