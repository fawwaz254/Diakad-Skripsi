<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKegiatanGuruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_guru', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kegiatan_guru', 40)->primary();
            $table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
            $table->date('tgl_kegiatan')->nullable();
            $table->string('nm_kegiatan', 128)->nullable()->comment('nama kegiatan (seminar lingkungan, workshop , dll)');
            $table->string('id_tingkat_prestasi_siswa', 40)->comment('FK: tingkat_prestasi_siswa.id_tingkat_prestasi_siswa');
            $table->string('link_kegiatan', 128)->nullable();
            $table->tinyInteger('status')->comment('0 = Belum diapprove; 2 = Sudah diapprove;');
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
        Schema::dropIfExists('kegiatan_guru');
    }
}
