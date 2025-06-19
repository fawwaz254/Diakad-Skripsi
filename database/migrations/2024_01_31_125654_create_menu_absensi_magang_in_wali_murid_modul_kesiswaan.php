<?php

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Sekolah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuAbsensiMagangInWaliMuridModulKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $sekolah_now = Sekolah::first();
        // if(str_contains($sekolah_now->nm_sekolah,'SMK')){
        //     $modul = Modul::where('nm_modul', 'Kesiswaan')->where('id_role',4)->first();
        //     $menu1 = new Menu();
        //     $menu1->id_modul = $modul->id_modul;
        //     $menu1->nm_menu = 'Presensi Magang';
        //     $menu1->page = 'absensi-magang';
        //     $menu1->urutan = 6;
        //     $menu1->akses = 1;
        //     $menu1->save();
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
