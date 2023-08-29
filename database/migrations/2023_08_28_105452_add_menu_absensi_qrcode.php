<?php

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuAbsensiQrcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::where('path','guru')->first();
        $modul = Modul::where('id_role',$role->id_role)->where('nm_modul','Presensi Kelas')->first();
        $menu = new Menu;
        $menu->id_modul = $modul->id_modul;
        $menu->nm_menu = 'Absensi Kode QR';
        $menu->page = 'absensi-kode-qr';
        $menu->urutan = 5;
        $menu->akses = 1;
        $menu->save();

        //
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
