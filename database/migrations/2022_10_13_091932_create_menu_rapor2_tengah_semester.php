<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Modul;

class CreateMenuRapor2TengahSemester extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Rapor Sisipan')->where('id_role', '2')->first();
        //ini menu khusus untuk ypm 3
        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Rapor Tengah Semester';
        $menu->page = 'rapor-tengah-semester';
        $menu->urutan = 3;
        $menu->akses = 0;
        $menu->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('menu_rapor2_tengah_semester');
    }
}
