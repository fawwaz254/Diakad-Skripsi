<?php

use App\Models\KelompokTambahanRapor;
use App\Models\Sekolah;
use App\Models\TambahanRapor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AddDataInTambahanRapor extends Migration
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
        $kelompok_tambahan_rapor = KelompokTambahanRapor::where('urutan', 1)->first();
        if ($kelompok_tambahan_rapor) {
            $tambahan_rapor = new TambahanRapor;
            $tambahan_rapor->id_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
            $tambahan_rapor->id_kelompok_tambahan_rapor = $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor;
            $tambahan_rapor->nm_tambahan_rapor = 'Sikap';
            $tambahan_rapor->urutan = '1';
            $tambahan_rapor->save();
        }

        //----------------------------------------------------------------------------
        $kelompok_tambahan_rapor = KelompokTambahanRapor::where('urutan', 2)->first();
        if ($kelompok_tambahan_rapor) {
            $tambahan_rapor = new TambahanRapor;
            $tambahan_rapor->id_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
            $tambahan_rapor->id_kelompok_tambahan_rapor = $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor;
            $tambahan_rapor->nm_tambahan_rapor = 'Sakit';
            $tambahan_rapor->urutan = '1';
            $tambahan_rapor->save();

            //------------------------------1
            $tambahan_rapor = new TambahanRapor;
            $tambahan_rapor->id_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
            $tambahan_rapor->id_kelompok_tambahan_rapor = $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor;
            $tambahan_rapor->nm_tambahan_rapor = 'Ijin';
            $tambahan_rapor->urutan = '2';
            $tambahan_rapor->save();

            //------------------------------1
            $tambahan_rapor = new TambahanRapor;
            $tambahan_rapor->id_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
            $tambahan_rapor->id_kelompok_tambahan_rapor = $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor;
            $tambahan_rapor->nm_tambahan_rapor = 'Tanpa Keterangan';
            $tambahan_rapor->urutan = '3';
            $tambahan_rapor->save();
        }
        //-----------------------------------------------------------------------------
        $kelompok_tambahan_rapor = KelompokTambahanRapor::where('urutan', 3)->first();
        if ($kelompok_tambahan_rapor) {
            $tambahan_rapor = new TambahanRapor;
            $tambahan_rapor->id_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
            $tambahan_rapor->id_kelompok_tambahan_rapor = $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor;
            $tambahan_rapor->nm_tambahan_rapor = 'Catatan Wali Kelas';
            $tambahan_rapor->urutan = '1';
            $tambahan_rapor->save();
            $tambahan_rapor = null;
        }

        $kelompok_tambahan_rapor = KelompokTambahanRapor::where('urutan', 4)->first();
        if ($kelompok_tambahan_rapor) {
            $tambahan_rapor = new TambahanRapor;
            $tambahan_rapor->id_tambahan_rapor = $sekolah->prefix . strtotime($now) . uniqid();
            $tambahan_rapor->id_kelompok_tambahan_rapor = $kelompok_tambahan_rapor->id_kelompok_tambahan_rapor;
            $tambahan_rapor->nm_tambahan_rapor = 'Kelulusan';
            $tambahan_rapor->urutan = '1';
            $tambahan_rapor->save();
            $tambahan_rapor = null;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
