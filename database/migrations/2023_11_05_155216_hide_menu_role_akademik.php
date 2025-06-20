<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideMenuRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul_data_akademik = Modul::where('id_role', 7)->where('nm_modul', 'Data Akademik')->first();
        // if ($modul_data_akademik) {
        //     //Aktivasi Kurikulum
        //     $menu = Menu::where('id_modul', $modul_data_akademik->id_modul)->where('nm_menu', 'Aktivasi Kurikulum')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }
        // }

        // //Aktivitas Semester
        // $modul_aktivitas_semester = Modul::where('id_role', 7)->where('nm_modul', 'Aktivitas Semester')->first();
        // if ($modul_aktivitas_semester) {
        //     //View Jadwal Kelas
        //     $menu = Menu::where('id_modul', $modul_aktivitas_semester->id_modul)->where('nm_menu', 'View Jadwal Kelas')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }

        //     //Total Jadwal Kelas
        //     $menu = Menu::where('id_modul', $modul_aktivitas_semester->id_modul)->where('nm_menu', 'Total Jadwal Kelas')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }
        //     //Cari Siswa
        //     $menu = Menu::where('id_modul', $modul_aktivitas_semester->id_modul)->where('nm_menu', 'Cari Siswa')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }
        //     //Input Nilai
        //     $menu = Menu::where('id_modul', $modul_aktivitas_semester->id_modul)->where('nm_menu', 'Input Nilai')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }
        // }
        // //modul Ujian
        // $modul_ujian = Modul::where('id_role', 7)->where('nm_modul', 'Ujian')->first();
        // if ($modul_ujian) {
        //     $modul_ujian->akses = '0';
        //     $modul_ujian->save();
        // }
        // //modul Presensi
        // $modul_presensi = Modul::where('id_role', 7)->where('nm_modul', 'Presensi')->first();
        // if ($modul_presensi) {
        //     $modul_presensi->akses = '0';
        //     $modul_presensi->save();
        // }

        // //modul Kelas Daring
        // $modul_kelas_daring = Modul::where('id_role', 7)->where('nm_modul', 'Kelas Daring')->first();
        // if ($modul_kelas_daring) {
        //     $modul_kelas_daring->akses = '0';
        //     $modul_kelas_daring->save();
        // }

        // //Laporan
        // $modul_laporan = Modul::where('id_role', 7)->where('nm_modul', 'Laporan')->first();
        // if ($modul_laporan) {
        //     $modul_laporan->akses = '0';
        //     $modul_laporan->save();
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
