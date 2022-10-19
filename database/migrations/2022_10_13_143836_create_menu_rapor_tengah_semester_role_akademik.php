<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;

class CreateMenuRaporTengahSemesterRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Rapor Sisipan')->where('id_role', '7')->first();

        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Nilai Rapor Tengah Semester';
        $menu->page = 'nilai-rapor-tengah-semester';
        $menu->urutan = 3;
        $menu->akses = 0;
        $menu->save();

        $menu2 = new Menu;
        $menu2->id_modul = $modul->id_modul;
        $menu2->nm_menu = 'Cetak Rapor Tengah Semester';
        $menu2->page = 'cetak-rapor-tengah-semester';
        $menu2->urutan = 4;
        $menu2->akses = 0;
        $menu2->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    //     Schema::dropIfExists('menu_rapor_tengah_semester_role_akademik');
    }
}
