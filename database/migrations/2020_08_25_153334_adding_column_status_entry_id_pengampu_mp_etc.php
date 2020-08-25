<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingColumnStatusEntryIdPengampuMpEtc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas_mp', function (Blueprint $table) {
            $table->boolean('status_entry')->after('id_kelas_mp_grup')->default('1')->comment('1: Akademik; 2: Guru;');
        });

        Schema::table('presensi_mp', function (Blueprint $table) {
            $table->string('id_pengampu_mp', 40)->after('id_jadwal_kelas_mp')->nullable()->comment('FK: pengampu_mp.id_pengampu_mp');
        });

        Schema::table('presensi_mp_siswa', function (Blueprint $table) {
            $table->boolean('kehadiran')->nullable()->comment('1 = hadir; 2 = sakit, tidak hadir; 3 = izin, tidak hadir; 4 = tanpa keterangan, tidak hadir; 5 = terlambat; 99 = Beda kelas;')->change();
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
