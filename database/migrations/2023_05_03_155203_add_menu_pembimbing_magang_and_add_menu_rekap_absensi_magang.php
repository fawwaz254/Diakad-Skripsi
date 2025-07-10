<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuPembimbingMagangAndAddMenuRekapAbsensiMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('nm_modul', 'Magang Siswa')->first();

        // $menu1 = new Menu;
        // $menu1->id_modul = $modul->id_modul;
        // $menu1->nm_menu = 'Pembimbing Magang';
        // $menu1->page = 'pembimbing-magang';
        // $menu1->urutan = 5;
        // $menu1->akses = 1;
        // $menu1->save();

        // $menu2 = new Menu;
        // $menu2->id_modul = $modul->id_modul;
        // $menu2->nm_menu = 'Rekap Absensi Magang';
        // $menu2->page = 'rekap-absensi-magang';
        // $menu2->urutan = 6;
        // $menu2->akses = 1;
        // $menu2->save();


        // // REORDER MENU IN MODUL KESISWAAN
        // $menu3 = Menu::where('nm_menu', 'Komponen Nilai Magang')->first();
        // if ($menu3) {
        //     $menu3->urutan = 7;
        //     $menu3->save();
        // }

        // $menu4 = Menu::where('nm_menu', 'Input Nilai Magang')->first();
        // if ($menu4) {
        //     $menu4->urutan = 8;
        //     $menu4->save();
        // }

        // $menu5 = Menu::where('nm_menu', 'Laporan Magang')->first();
        // if ($menu5) {
        //     $menu5->urutan = 9;
        //     $menu5->save();
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
