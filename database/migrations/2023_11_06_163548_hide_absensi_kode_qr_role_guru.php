<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideAbsensiKodeQrRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul_presensi_kelas = Modul::where('id_role', 2)->where('nm_modul', 'Presensi Kelas')->first();
        // if ($modul_presensi_kelas) {
        //     //Absensi Kode Qr
        //     $menu = Menu::where('id_modul', $modul_presensi_kelas->id_modul)->where('nm_menu', 'Absensi Kode QR')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }
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
