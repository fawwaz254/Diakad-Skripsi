<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideModulAndMenuNotUseInRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul_faq = Modul::where('id_role', 2)->where('nm_modul', 'FAQ')->first();
        if ($modul_faq) {
            $modul_faq->akses = '0';
            $modul_faq->save();
        }

        $modul_kesekretariatan = Modul::where('id_role', 2)->where('nm_modul', 'Kesekretariatan')->first();
        if ($modul_kesekretariatan) {
            $modul_kesekretariatan->akses = '0';
            $modul_kesekretariatan->save();
        }
        //Tutorial
        $modul_tutorial = Modul::where('id_role', 2)->where('nm_modul', 'Tutorial')->first();
        if ($modul_tutorial) {
            $modul_tutorial->akses = '0';
            $modul_tutorial->save();
        }

        $modul_wali_kelas = Modul::where('id_role', 2)->where('nm_modul', 'Wali Kelas')->first();
        if ($modul_wali_kelas) {
            $menu = Menu::where('id_modul', $modul_wali_kelas->id_modul)->where('nm_menu', 'Rekap Kesehatan Siswa')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            $menu = Menu::where('id_modul', $modul_wali_kelas->id_modul)->where('nm_menu', 'Rekap Absensi Kelas Daring')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
