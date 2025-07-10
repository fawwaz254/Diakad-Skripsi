<?php

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuTandaTanganDigitalSekretariat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $now = Carbon::now();

        // $id_modul = Modul::where('id_role', 14)->where('nm_modul', 'Manajemen Tanda Tangan')->first()->id_modul;

        // $menu = new Menu;
        // $menu->id_modul = $id_modul;
        // $menu->nm_menu = 'Tanda Tangan Digital';
        // $menu->page = 'tanda-tangan-digital';
        // $menu->urutan = 1;
        // $menu->akses = 1;
        // $menu->save();
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
