<?php

use App\Models\KelompokMapelRapor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokSisipan;
use App\Models\Sekolah;

class AddDataKelompokMapelRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $sekolah = Sekolah::first();
        $kelompok_mapel_rapor = new KelompokMapelRapor;
        $kelompok_mapel_rapor->id_kelompok_mapel_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_mapel_rapor->nm_kelompok_mapel_rapor = 'Kelompok A ( UMUM )';
        $kelompok_mapel_rapor->urutan = '1';
        $kelompok_mapel_rapor->save();
        $kelompok_mapel_rapor = null;

        $kelompok_mapel_rapor = new KelompokMapelRapor;
        $kelompok_mapel_rapor->id_kelompok_mapel_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_mapel_rapor->nm_kelompok_mapel_rapor = 'Kelompok B ( UMUM )';
        $kelompok_mapel_rapor->urutan = '2';
        $kelompok_mapel_rapor->save();
        $kelompok_mapel_rapor = null;

        $kelompok_mapel_rapor = new KelompokMapelRapor;
        $kelompok_mapel_rapor->id_kelompok_mapel_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_mapel_rapor->nm_kelompok_mapel_rapor = 'Kelompok C ( Peminatan )';
        $kelompok_mapel_rapor->urutan = '3';
        $kelompok_mapel_rapor->save();
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
