<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKegiatanSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_siswa', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kegiatan_siswa', 40)->primary();
            $table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
            $table->string('id_kelas', 40)->comment('FK: kelas.id_kelas, ini kelas siswa saat melakukan aksi');
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->date('tgl_kegiatan_siswa')->nullable();
            $table->string('nm_kegiatan_siswa', 128)->nullable()->comment('nama kegiatan (seminar lingkungan, workshop , dll)');
            $table->string('id_tingkat_prestasi_siswa', 40)->comment('FK: tingkat_prestasi_siswa.id_tingkat_prestasi_siswa');
            $table->string('nm_kegiatan_scan_sertif', 128)->nullable();
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
        Schema::dropIfExists('kegiatan_siswa');
    }
}
