<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdJadwalKelasMpOnPresensiMp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensi_mp', function (Blueprint $table) {
            $table->string('id_jadwal_kelas_mp', 40)->after('id_kelas_mp')->comment('FK: jadwal_kelas_mp.id_jadwal_kelas_mp');
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
