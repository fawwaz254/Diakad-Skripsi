<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideMenuDaftarNilaiAkhirSemester extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $menu = Menu::where('nm_menu', 'Daftar Nilai Akhir Semester')->get();
        // foreach ($menu as $m) {
        //     $m->akses = '0';
        //     $m->save();
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
