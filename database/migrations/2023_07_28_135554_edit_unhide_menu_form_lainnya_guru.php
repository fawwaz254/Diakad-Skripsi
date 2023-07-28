<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditUnhideMenuFormLainnyaGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('route','kegiatan-harian')->where('id_role',2)->first();
        $menu = Menu::where('id_modul',$modul->id_modul)->where('page','form-lainnya')->first();
        $menu->akses = 1;
        $menu->save();

        $menu1 = Menu::where('id_modul',$modul->id_modul)->where('page','mengisi-form-kesehatan')->first();
        $menu1->akses = 0;
        $menu1->save();
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
