<?php

use App\Models\Pengguna;
use App\Models\Sekolah;
use App\Models\StatusPengguna;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;


class AddAkunAdminYayasanToPenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('pengguna', function (Blueprint $table) {
        //     $now = Carbon::now();
        //     $status = StatusPengguna::where('status_join_table', 1)->where('nm_status_pengguna', 'AKTIF')->first();
        //     $sekolah = Sekolah::first();
        //     $pengguna = new Pengguna;
        //     $pengguna->id_pengguna = $sekolah->prefix . strtotime($now) . uniqid();
        //     $pengguna->id_status_pengguna = $status->id_status_pengguna;
        //     $pengguna->id_sekolah = $sekolah->id_sekolah;
        //     $pengguna->nm_pengguna = 'Admin YPM';
        //     $pengguna->username = 'yayasan';
        //     $pengguna->password = Hash::make('passwordyayasan');
        //     $pengguna->must_change_password = 0;
        //     $pengguna->status_join_table = 1;
        //     $pengguna->save();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengguna', function (Blueprint $table) {
            //
        });
    }
}
