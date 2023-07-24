<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditModulPresensiGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('route','presensi')->where('id_role',2)->first();
        $modul->nm_modul = 'Presensi Kelas';
        $modul->save();

        $menu1 = Menu::where('id_modul',$modul->id_modul)->where('page','absensi-siswa')->first();
        $menu1->nm_menu = 'Absensi Siswa Kelas';
        $menu1->save();

        $menu2 = Menu::where('id_modul',$modul->id_modul)->where('page','rekap-absen')->first();
        $menu2->nm_menu = 'Rekap Absen Kelas';
        $menu2->save();
        
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
