<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\KelompokKPI;
use App\Models\Sekolah;


class AddDataInKelompokKpi2 extends Migration
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
        $kelompok_kpi = new KelompokKPI;
        $kelompok_kpi->id_kelompok_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_kpi->nm_kelompok_kpi = 'Kecakapan Penerapan Ibadah';
        $kelompok_kpi->urutan = '1';
        $kelompok_kpi->nm_header_table_point = 'Kecakapan';
        $kelompok_kpi->nm_header_table_deskripsi = 'Deskripsi Kecakapan Penerapan Ibadah';
        $kelompok_kpi->save();

        $kelompok_kpi = new KelompokKPI;
        $kelompok_kpi->id_kelompok_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_kpi->nm_kelompok_kpi = 'Pelaksanaan Shalat Lima Waktu (Berdasar Pengakuan Siswa)';
        $kelompok_kpi->urutan = '2';
        $kelompok_kpi->nm_header_table_point = 'Pelaksanaan Shalat Lima Waktu';
        $kelompok_kpi->nm_header_table_deskripsi = 'Diskripsi Pelaksanaan Shalat Lima Waktu';
        $kelompok_kpi->save();

        $kelompok_kpi = new KelompokKPI;
        $kelompok_kpi->id_kelompok_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_kpi->nm_kelompok_kpi = 'Pelaksanaan Mengaji Harian Di Rumah (Berdasar Pengakuan Siswa)';
        $kelompok_kpi->urutan = '3';
        $kelompok_kpi->nm_header_table_point = 'Pelaksanaan Mengaji';
        $kelompok_kpi->nm_header_table_deskripsi = 'Diskripsi Pelaksanaan Mengaji Harian Di Rumah';
        $kelompok_kpi->save();

        $kelompok_kpi = new KelompokKPI;
        $kelompok_kpi->id_kelompok_kpi = $sekolah->prefix . strtotime($now) . uniqid();
        $kelompok_kpi->nm_kelompok_kpi = "Penguasaan Hafalan Surat-Surat Dalam Al-Qur'an";
        $kelompok_kpi->urutan = '4';
        $kelompok_kpi->nm_header_table_point = 'Surat';
        $kelompok_kpi->nm_header_table_deskripsi = 'Diskripsi Penguasaan Hafalan';
        $kelompok_kpi->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
