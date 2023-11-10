<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideModulAndMenuNotUseInRoleSumberDaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul_data_sumber_daya = Modul::where('id_role', 8)->where('nm_modul', 'Data Sumber Daya')->first();
        if ($modul_data_sumber_daya) {
            $menu = Menu::where('id_modul', $modul_data_sumber_daya->id_modul)->where('nm_menu', 'Data Unit Kerja')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            $menu = Menu::where('id_modul', $modul_data_sumber_daya->id_modul)->where('nm_menu', 'Data Status Aktif Guru')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            $menu = Menu::where('id_modul', $modul_data_sumber_daya->id_modul)->where('nm_menu', 'Data Status Aktif Tendik')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
        }

        $modul_data_guru = Modul::where('id_role', 8)->where('nm_modul', 'Data Guru')->first();
        if ($modul_data_guru) {
            $modul_data_guru->akses = '0';
            $modul_data_guru->save();
        }

        $modul_data_tenaga_pendidikan = Modul::where('id_role', 8)->where('nm_modul', 'Data Tenaga Kependidikan')->first();
        if ($modul_data_tenaga_pendidikan) {
            $modul_data_tenaga_pendidikan->akses = '0';
            $modul_data_tenaga_pendidikan->save();
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
