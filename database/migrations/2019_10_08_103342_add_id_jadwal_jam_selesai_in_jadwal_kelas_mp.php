<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdJadwalJamSelesaiInJadwalKelasMp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('jadwal_kelas_mp', function (Blueprint $table) {
            $table->string('id_jadwal_jam_selesai', 40)->after('id_jadwal_jam')->comment('FK: jadwal_jam.id_jadwal_jam, ini jadwal jam mapel terakhir');
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
