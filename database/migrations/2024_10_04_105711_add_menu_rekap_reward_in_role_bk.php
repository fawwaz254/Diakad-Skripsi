<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuRekapRewardInRoleBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Reward Siswa')->where('id_role', 5)->first();

        if ($modul) {
            $menu = new Menu();
            $menu->id_modul = $modul->id_modul;
            $menu->nm_menu = 'Rekap Reward Siswa';
            $menu->page = 'rekap-reward-siswa';
            $menu->urutan = 2;
            $menu->akses = 1;
            $menu->save();
        } else {
            throw new \Exception("Modul 'Reward Siswa' untuk role 5 tidak ditemukan");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $modul = Modul::where('nm_modul', 'Reward Siswa')->where('id_role', 5)->first();

        if ($modul) {
            Menu::where('id_modul', $modul->id_modul)
                ->where('nm_menu', 'Rekap Reward Siswa')
                ->delete();
        }
    }
}
