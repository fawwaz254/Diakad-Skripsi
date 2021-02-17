<?php

use App\Models\JadwalKegiatan;
use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingDeskripsiToJadwalKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwal_kegiatan', function (Blueprint $table) {
            $table->text('deskripsi')->after('id_semester')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal_kegiatan', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
       });
    }
}
