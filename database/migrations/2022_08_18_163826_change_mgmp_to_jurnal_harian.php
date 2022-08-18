<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMgmpToJurnalHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //ganti nama mgmp di role guru
        $modul1 = Modul::where('id_role', 2)->where('nm_modul', 'MGMP')->first();
        $menu1 = Menu::where('id_modul', $modul1->id_modul)->where('nm_menu', 'Laporan Individu MGMP')->first();
        $menu1->update([
            "nm_menu" => 'Laporan Individu Jurnal Harian'
        ]);
        $menu2 = Menu::where('id_modul', $modul1->id_modul)->where('nm_menu', 'Laporan Kelompok MGMP')->first();
        $menu2->update([
            "nm_menu" => 'Laporan Kelompok Jurnal Harian'
        ]);
        $modul1->update([
            "nm_modul" => 'Jurnal Harian'
        ]);

        //ganti nama mgmp di role akademik
        $modul2 = Modul::where('id_role', 7)->where('nm_modul', 'MGMP')->first();
        $menu3 = Menu::where('id_modul', $modul2->id_modul)->where('nm_menu', 'Kelompok Guru MGMP')->first();
        $menu3->update([
            "nm_menu" => 'Kelompok Jurnal Harian Guru '
        ]);
        $menu4 = Menu::where('id_modul', $modul2->id_modul)->where('nm_menu', 'Laporan MGMP')->first();
        $menu4->update([
            "nm_menu" => 'Laporan Jurnal Harian'
        ]);

        $menu5 = Menu::where('id_modul', $modul2->id_modul)->where('nm_menu', 'Jenis MGMP')->first();
        $menu5->update([
            "nm_menu" => 'Jenis Jurnal Harian'
        ]);

        $modul2->update([
            "nm_modul" => 'Jurnal Harian'
        ]);
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
