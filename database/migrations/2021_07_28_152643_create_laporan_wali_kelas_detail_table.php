<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaporanWaliKelasDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_wali_kelas_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_laporan_wali_kelas_detail', 40)->primary();
            $table->string('id_laporan_wali_kelas', 40)->comment('FK: laporan_wali_kelas.id_laporan_wali_kelas');
            $table->string('id_guru', 40)->comment('FK: guru.id_guru');
            $table->string('id_kelas', 40)->comment('FK: kelas.id_kelas');
            $table->tinyInteger('status')->nullable()->comment('0=Belum Tuntas, 1 = Tuntas');
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('laporan_wali_kelas_detail');
    }
}
