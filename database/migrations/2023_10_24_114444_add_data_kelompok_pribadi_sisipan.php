<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\KelompokPribadiSisipan;
use App\Models\Sekolah;

class AddDataKelompokPribadiSisipan extends Migration
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
        // $kelompok_pribadi_sisipan = new KelompokPribadiSisipan;
        // $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $kelompok_pribadi_sisipan->nm_kelompok_pribadi_sisipan = 'Kepribadian';
        // $kelompok_pribadi_sisipan->urutan = '1';
        // $kelompok_pribadi_sisipan->save();
        // $kelompok_pribadi_sisipan = null;

        // $kelompok_pribadi_sisipan = new KelompokPribadiSisipan;
        // $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $kelompok_pribadi_sisipan->nm_kelompok_pribadi_sisipan = 'Ketidak Hadiran';
        // $kelompok_pribadi_sisipan->urutan = '2';
        // $kelompok_pribadi_sisipan->save();
        // $kelompok_pribadi_sisipan = null;

        // $kelompok_pribadi_sisipan = new KelompokPribadiSisipan;
        // $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
        // $kelompok_pribadi_sisipan->nm_kelompok_pribadi_sisipan = 'Ekstra Kurikuler';
        // $kelompok_pribadi_sisipan->urutan = '3';
        // $kelompok_pribadi_sisipan->save();
        // $kelompok_pribadi_sisipan = null;
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
