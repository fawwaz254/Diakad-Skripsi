<?php

use App\Models\JenisRapor;
use App\Models\Kelas;
use App\Models\KomponenJenisRapor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Sekolah;

class AddDataJenisRaporAndDataKomponenJenisRapor extends Migration
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

        //jenis merdeka
        $jenis_rapor = new JenisRapor;
        $jenis_rapor->id_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $jenis_rapor->nm_jenis_rapor = 'Merdeka';
        $jenis_rapor->created_by = 'migration';
        $jenis_rapor->save();

        $komponen_jenis_rapor = new KomponenJenisRapor;
        $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $komponen_jenis_rapor->id_jenis_rapor =  $jenis_rapor->id_jenis_rapor;
        $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'Pengetahuan';
        $komponen_jenis_rapor->created_by = 'migration';
        $komponen_jenis_rapor->save();

        $komponen_jenis_rapor = new KomponenJenisRapor;
        $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $komponen_jenis_rapor->id_jenis_rapor =  $jenis_rapor->id_jenis_rapor;
        $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'Keterampilan';
        $komponen_jenis_rapor->created_by = 'migration';
        $komponen_jenis_rapor->save();
        //jenis k-13

        $jenis_rapor = new JenisRapor;
        $jenis_rapor->id_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $jenis_rapor->nm_jenis_rapor = 'K13';
        $jenis_rapor->created_by = 'migration';
        $jenis_rapor->save();

        $komponen_jenis_rapor = new KomponenJenisRapor;
        $komponen_jenis_rapor->id_komponen_jenis_rapor = $sekolah->prefix . strtotime($now) . uniqid();
        $komponen_jenis_rapor->id_jenis_rapor =  $jenis_rapor->id_jenis_rapor;
        $komponen_jenis_rapor->nm_komponen_jenis_rapor = 'Nilai';
        $komponen_jenis_rapor->created_by = 'migration';
        $komponen_jenis_rapor->save();


        $kelas = Kelas::where('is_aktif', 1)->get();
        foreach ($kelas as $k) {
            $k->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
            $k->save();
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
