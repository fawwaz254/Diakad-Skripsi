<?php

use App\Models\KelompokPribadiSisipan;
use App\Models\PribadiSisipan;
use App\Models\Sekolah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AddKelompokPribadiSisipanCatatanUntukOrangTua extends Migration
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
        // $kelompok_pribadi_sisipan = new KelompokPribadiSisipan();
        // $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $kelompok_pribadi_sisipan->nm_kelompok_pribadi_sisipan = 'Catatan Untuk Orang Tua';
        // $kelompok_pribadi_sisipan->urutan = '4';
        // $kelompok_pribadi_sisipan->save();

        // $pribadi_sisipan = new PribadiSisipan();
        // $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan;
        // $pribadi_sisipan->nm_pribadi_sisipan = 'Catatan Untuk Orang Tua';
        // $pribadi_sisipan->urutan = '1';
        // $pribadi_sisipan->save();
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
