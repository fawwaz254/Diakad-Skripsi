<?php

use App\Models\Sekolah;
use App\Models\Semester;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AddTahunAjaran20232024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $validasi = Semester::where('tahun_ajaran', '2023/2024')->first();
        // if (!$validasi) {
        //     $sekolah_data = Sekolah::first();
        //     $now = Carbon::now();
        //     //semester ganjil
        //     $semester = new Semester;
        //     $semester->id_semester = $sekolah_data->prefix . strtotime($now) . uniqid();
        //     $semester->nm_semester = "Ganjil";
        //     $semester->thn_akademik_semester = "2023";
        //     $semester->tahun_ajaran = "2023/2024";
        //     $semester->is_aktif_semester = 0;
        //     $semester->kode_semester = 20231;
        //     $semester->id_sekolah = $sekolah_data->id_sekolah;
        //     $semester->created_at = $now;
        //     $semester->created_by = 'migration';
        //     $semester->save();

        //     //semester genap
        //     $semester2 = new Semester;
        //     $semester2->id_semester = $sekolah_data->prefix . strtotime($now) . uniqid();
        //     $semester2->nm_semester = "Genap";
        //     $semester2->thn_akademik_semester = "2023";
        //     $semester2->tahun_ajaran = "2023/2024";
        //     $semester2->is_aktif_semester = 0;
        //     $semester2->kode_semester = 20232;
        //     $semester2->id_sekolah = $sekolah_data->id_sekolah;
        //     $semester2->created_at = $now;
        //     $semester2->created_by = 'migration';
        //     $semester2->save();
        // }
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
