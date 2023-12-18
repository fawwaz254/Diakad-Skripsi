<?php

use App\Models\JenisRapor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sekolah;
use Carbon\Carbon;

class AddDataJenisRaporAgamaAndSisipan extends Migration
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
        $jenis_rapor = new JenisRapor;
        $jenis_rapor->id_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $jenis_rapor->nm_jenis_rapor = 'agama';
        $jenis_rapor->save();
        $jenis_rapor = new JenisRapor;
        $jenis_rapor->id_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $jenis_rapor->nm_jenis_rapor = 'sisipan';
        $jenis_rapor->save();
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
