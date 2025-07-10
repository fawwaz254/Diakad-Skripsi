<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelasMpGrupMateriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_mp_grup_materi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kelas_mp_grup_materi', 40)->primary();
            $table->string('id_kelas_mp_grup', 40)->comment('FK: kelas_mp_grup.id_kelas_mp_grup');
            $table->boolean('pertemuan_ke')->nullable();
            $table->longText('uraian_materi')->nullable()->comment('input lewat CKEditor, isian tulisan materi dan/atau gambar dan embed video, utk file lainnya ada di tabel keas_mp_grup_file');
            $table->timestamp('tgl_rencana_mulai')->nullable();
            $table->timestamp('tgl_rencana_selesai')->nullable();
            $table->timestamp('tgl_pelaksanaan_mulai')->nullable();
            $table->timestamp('tgl_pelaksanaan_selesai')->nullable();
            $table->decimal('persentase_kehadiran_siswa', 10, 0)->nullable();
            $table->string('keterangan', 2048)->nullable();
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
        Schema::dropIfExists('kelas_mp_grup_materi');
    }
}
