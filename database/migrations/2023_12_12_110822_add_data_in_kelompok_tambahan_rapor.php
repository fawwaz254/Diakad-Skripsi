<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokSisipan;
use App\Models\KelompokTambahanRapor;
use App\Models\Sekolah;

class AddDataInKelompokTambahanRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
        $sekolah = Sekolah::first();
        $kelompok_tambahan_rapor = new KelompokTambahanRapor;
        $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_tambahan_rapor->nm_kelompok_tambahan_rapor = 'Sikap';
        $kelompok_tambahan_rapor->urutan = '1';
        $kelompok_tambahan_rapor->save();


        $kelompok_tambahan_rapor = new KelompokTambahanRapor;
        $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_tambahan_rapor->nm_kelompok_tambahan_rapor = 'Ketidak Hadiran';
        $kelompok_tambahan_rapor->urutan = '2';
        $kelompok_tambahan_rapor->save();


        $kelompok_tambahan_rapor = new KelompokTambahanRapor;
        $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_tambahan_rapor->nm_kelompok_tambahan_rapor = 'Catatan Wali Kelas';
        $kelompok_tambahan_rapor->urutan = '3';
        $kelompok_tambahan_rapor->save();


        $kelompok_tambahan_rapor = new KelompokTambahanRapor;
        $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_tambahan_rapor->nm_kelompok_tambahan_rapor = 'Kelulusan';
        $kelompok_tambahan_rapor->urutan = '4';
        $kelompok_tambahan_rapor->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('kelompok_tambahan_rapor', function (Blueprint $table) {
        //     //
        // });
    }
}
