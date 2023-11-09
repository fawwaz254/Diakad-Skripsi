<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideMenuRolePendidikan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul_data_akademik = Modul::where('id_role', 1)->where('nm_modul', 'Data Akademik')->first();
        if ($modul_data_akademik) {
            //Nilai Mutu
            $menu = Menu::where('id_modul', $modul_data_akademik->id_modul)->where('nm_menu', 'Nilai Mutu')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Rentang Nilai Mutu
            $menu = Menu::where('id_modul', $modul_data_akademik->id_modul)->where('nm_menu', 'Rentang Nilai Mutu')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Data Jalur
            $menu = Menu::where('id_modul', $modul_data_akademik->id_modul)->where('nm_menu', 'Data Jalur')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Data Kegiatan
            $menu = Menu::where('id_modul', $modul_data_akademik->id_modul)->where('nm_menu', 'Data Kegiatan')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
        }

        //Setting Kelas
        $modul_setting_kelas = Modul::where('id_role', 1)->where('nm_modul', 'Setting Kelas')->first();
        if ($modul_setting_kelas) {
            //Setting Wali Kelas
            $menu = Menu::where('id_modul', $modul_setting_kelas->id_modul)->where('nm_menu', 'Setting Wali Kelas')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Setting Sekretaris Kelas
            $menu = Menu::where('id_modul', $modul_setting_kelas->id_modul)->where('nm_menu', 'Setting Sekretaris Kelas')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Setting Ruangan Kelas
            $menu = Menu::where('id_modul', $modul_setting_kelas->id_modul)->where('nm_menu', 'Setting Ruangan Kelas')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Setting BK Kelas
            $menu = Menu::where('id_modul', $modul_setting_kelas->id_modul)->where('nm_menu', 'Setting BK Kelas')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
        }

        $modul_siswa = Modul::where('id_role', 1)->where('nm_modul', 'Siswa')->first();
        if ($modul_siswa) {
            $modul_siswa->akses = '0';
            $modul_siswa->save();
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
