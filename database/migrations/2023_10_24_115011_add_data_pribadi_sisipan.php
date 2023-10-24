<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\KelompokPribadiSisipan;
use App\Models\PribadiSisipan;
use App\Models\Sekolah;

class AddDataPribadiSisipan extends Migration
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
        $kelompok_pribadi_siswa1 = KelompokPribadiSisipan::where('urutan', 1)->first();
        if ($kelompok_pribadi_siswa1) {
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa1->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'a. Kelakuan';
            $pribadi_sisipan->urutan = '1';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa1->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'b. Kerajinan';
            $pribadi_sisipan->urutan = '2';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa1->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'c. Kerapian';
            $pribadi_sisipan->urutan = '3';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
        }

        //----------------------------------------------------------------------------
        $kelompok_pribadi_siswa2 = KelompokPribadiSisipan::where('urutan', 2)->first();
        if ($kelompok_pribadi_siswa2) {
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa2->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'a. Sakit';
            $pribadi_sisipan->urutan = '1';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa2->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'b. Ijin';
            $pribadi_sisipan->urutan = '2';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa2->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'c. Tanpa Keterangan';
            $pribadi_sisipan->urutan = '3';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
        }
        //-----------------------------------------------------------------------------
        $kelompok_pribadi_siswa3 = KelompokPribadiSisipan::where('urutan', 3)->first();
        if ($kelompok_pribadi_siswa2) {
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'Design Grafis';
            $pribadi_sisipan->urutan = '1';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'Menjahit';
            $pribadi_sisipan->urutan = '2';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'Banjari';
            $pribadi_sisipan->urutan = '3';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'Futsal';
            $pribadi_sisipan->urutan = '4';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'Pramuka';
            $pribadi_sisipan->urutan = '5';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'Menjahit';
            $pribadi_sisipan->urutan = '6';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'E-SPORT';
            $pribadi_sisipan->urutan = '7';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'PMR';
            $pribadi_sisipan->urutan = '8';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'BAND';
            $pribadi_sisipan->urutan = '9';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'PENCAK SILAT';
            $pribadi_sisipan->urutan = '10';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
            //------------------------------1
            $pribadi_sisipan = new PribadiSisipan;
            $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
            $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kelompok_pribadi_siswa3->id_kelompok_pribadi_sisipan;
            $pribadi_sisipan->nm_pribadi_sisipan = 'BULU TANGKIS';
            $pribadi_sisipan->urutan = '11';
            $pribadi_sisipan->save();
            $pribadi_sisipan = null;
        }
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
