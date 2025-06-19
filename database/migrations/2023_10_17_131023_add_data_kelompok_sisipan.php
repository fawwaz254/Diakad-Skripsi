<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokSisipan;
use App\Models\Sekolah;


class AddDataKelompokSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $now = Carbon::now();
        // $sekolah = Sekolah::first();
        // $kelompok_sisipan = new KelompokSisipan;
        // $kelompok_sisipan->id_kelompok_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $kelompok_sisipan->nm_kelompok_sisipan = 'Kelompok A ( UMUM )';
        // $kelompok_sisipan->urutan = '1';
        // $kelompok_sisipan->save();
        // $kelompok_sisipan = null;

        // $kelompok_sisipan = new KelompokSisipan;
        // $kelompok_sisipan->id_kelompok_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $kelompok_sisipan->nm_kelompok_sisipan = 'Kelompok B ( UMUM )';
        // $kelompok_sisipan->urutan = '2';
        // $kelompok_sisipan->save();
        // $kelompok_sisipan = null;

        // $kelompok_sisipan = new KelompokSisipan;
        // $kelompok_sisipan->id_kelompok_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $kelompok_sisipan->nm_kelompok_sisipan = 'Kelompok C ( Peminatan )';
        // $kelompok_sisipan->urutan = '3';
        // $kelompok_sisipan->save();
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
