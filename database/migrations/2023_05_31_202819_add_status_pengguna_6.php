<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pengguna;
use App\Models\Sekolah;
use App\Models\StatusPengguna;
use Carbon\Carbon;

class AddStatusPengguna6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sekolah = Sekolah::first();
        $pengguna = Pengguna::where('username', 'admin')->first();

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $status_pengguna = new StatusPengguna;
        $status_pengguna->id_status_pengguna = $sekolah->prefix . strtotime($now) . uniqid();
        $status_pengguna->status_join_table = 6;
        $status_pengguna->nm_status_pengguna = 'AKTIF';
        $status_pengguna->aktif_status_pengguna = 1;
        $status_pengguna->id_sekolah = $sekolah->id_sekolah;
        $status_pengguna->created_by = $pengguna->id_pengguna;
        $status_pengguna->save();

        $status_pengguna = new StatusPengguna;
        $status_pengguna->id_status_pengguna = $sekolah->prefix  . strtotime($now) . uniqid();
        $status_pengguna->status_join_table = 6;
        $status_pengguna->nm_status_pengguna = 'NON-AKTIF';
        $status_pengguna->aktif_status_pengguna = 0;
        $status_pengguna->id_sekolah = $sekolah->id_sekolah;
        $status_pengguna->created_by = $pengguna->id_pengguna;
        $status_pengguna->save();
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
