<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PreparingTableForDilearning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensi_mp', function (Blueprint $table) {
            $table->boolean('jenis_materi')->default(1)->after('pertemuan_ke')->comment('1 = KBM; 2 = UH; 3 = UTS; 4 = UAS;');
            $table->boolean('is_daring')->default(0)->after('jenis_materi')->comment('0 = Luring; 1 = Daring;');
            $table->integer('torelansi_terlambat')->after('is_daring')->nullable();
            $table->string('id_jadwal_kelas_mp', 40)->after('id_kelas_mp')->nullable()->comment('FK: jadwal_kelas_mp.id_jadwal_kelas_mp')->change();
        });

        Schema::create('presensi_mp_materi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_presensi_mp_materi', 40)->primary();
            $table->string('id_presensi_mp', 40)->comment('FK: presensi_mp.id_presensi_mp');
            $table->string('nm_materi', 2048);
            $table->string('link_materi', 2048);
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::create('presensi_mp_chat', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_presensi_mp_chat', 40)->primary();
            $table->string('id_presensi_mp', 40)->comment('FK: presensi_mp.id_presensi_mp');
            $table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
            $table->text('isi_chat', 65535)->nullable();
            $table->string('link_file_chat', 2048);
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::table('presensi_mp_siswa', function (Blueprint $table) {
            $table->boolean('kehadiran')->nullable()->comment('1 = hadir; 2 = sakit, tidak hadir; 3 = izin, tidak hadir; 4 = tanpa keterangan, tidak hadir; 5 = terlambat;')->change();
            $table->string('link_tugas', 2048)->after('alasan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
