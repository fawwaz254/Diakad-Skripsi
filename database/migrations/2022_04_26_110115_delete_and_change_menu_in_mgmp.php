<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAndChangeMenuInMgmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Menu::where('id_modul',116)->where('nm_menu','Data Sub Folder Kategori Mapel')->delete();
        Menu::where('id_modul',116)->where('nm_menu','Data File')->delete();
        Menu::where('id_modul', 116)->where('nm_menu', 'Data Kategori Mapel')->update(array(
            'nm_menu' => 'Kelompok Guru MGMP'
        ));
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
