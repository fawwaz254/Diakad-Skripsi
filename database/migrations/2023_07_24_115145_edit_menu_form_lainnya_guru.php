<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditMenuFormLainnyaGuru extends Migration
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
        //
    }
}
