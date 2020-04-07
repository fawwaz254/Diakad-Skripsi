cr<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelasMpGrupSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_mp_grup_siswa', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kelas_mp_grup_siswa', 40)->primary();
            $table->string('id_kelas_mp_grup_materi', 40)->comment('FK: kelas_mp_grup_materi.id_kelas_mp_grup_materi');
            $table->string('id_siswa', 40)->comment('FK: siswa.id_siswa');
            $table->boolean('kehadiran')->nullable()->comment('1 = hadir; 2 = sakit, tidak hadir; 3 = izin, tidak hadir; 4 = tanpa keterangan, tidak hadir;');
            $table->string('alasan', 256)->nullable()->comment('diisi apabila tidak hadir');
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
        Schema::dropIfExists('kelas_mp_grup_siswa');
    }
}
