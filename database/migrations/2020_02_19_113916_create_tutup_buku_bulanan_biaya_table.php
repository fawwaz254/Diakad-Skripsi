<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutupBukuBulananBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutup_buku_bulanan_biaya', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_tutup_buku_bulanan_biaya', 40)->primary();
            $table->string('id_semester_mulai', 40)->comment('FK: semester.id_semester');
            $table->string('id_semester_selesai', 40)->comment('FK: semester.id_semester');
            $table->boolean('id_bulan')->comment('1 = Januari, 2 = Februari, dst');
            $table->integer('tingkat')->comment('tingkat/angkatan')->nullable();
            $table->integer('jml_siswa')->nullable();
            $table->float('jml_tagihan_biaya', 10, 0)->nullable();
            $table->float('jml_pembayaran_biaya', 10, 0)->nullable();
            $table->float('jml_tunggakan_biaya', 10, 0)->nullable();
            $table->float('jml_pembayaran_biaya_bulan_lalu', 10, 0)->nullable();
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
        Schema::dropIfExists('tutup_buku_bulanan_biaya');
    }
}
