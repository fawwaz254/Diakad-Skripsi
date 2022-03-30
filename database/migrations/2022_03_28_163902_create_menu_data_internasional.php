<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;
use App\Models\Modul;
class CreateMenuDataInternasional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      

      


            $modul = Modul::where('nm_modul', 'SKPI')->where('id_role', '3')->first();

            $menu = new Menu;
            $menu->id_modul = $modul->id_modul;
            $menu->nm_menu = 'Informasi Tambahan';
            $menu->page = 'informasi_tambahan';
            $menu->urutan = 3;
            $menu->akses = 1;
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
