<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaporanKerjaHarianMGMPsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_kerja_harian_mgmp', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_laporan_kerja_harian_mgmp', 40)->primary();
            $table->string('id_role', 40)->nullable();
            $table->date('tanggal')->nullable();
            $table->string('jenis', 40)->nullable();
            $table->string('mapel', 40)->nullable();
            $table->text('keterangan_progres')->nullable();
            $table->string('nm_file', 256)->nullable();
            $table->string('path_file', 256)->nullable();
            $table->tinyInteger('status')->nullable()->comment('0=Belum Tuntas, 1 = Tuntas');
            $table->text('catatan')->nullable();
            $table->string('id_pengguna', 40)->nullable();
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
        Schema::dropIfExists('laporan_kerja_harian_mgmp');
    }
}
