<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideModulAndMenuNotUseInRoleSarpras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul_data_inventaris_bergerak = Modul::where('id_role', 10)->where('nm_modul', 'Data Inventaris Bergerak')->first();
        // if ($modul_data_inventaris_bergerak) {
        //     $modul_data_inventaris_bergerak->akses = '0';
        //     $modul_data_inventaris_bergerak->save();
        // }

        // $modul_data_sarpras_buku_alat = Modul::where('id_role', 10)->where('nm_modul', 'Data Sarpras Buku/Alat')->first();
        // if ($modul_data_sarpras_buku_alat) {
        //     $menu = Menu::where('id_modul', $modul_data_sarpras_buku_alat->id_modul)->where('nm_menu', 'Data Jenis Buku/Alat')->first();
        //     if ($menu) {
        //         $menu->nm_menu = 'Data Jenis Alat';
        //         $menu->save();
        //     }
        //     $menu = Menu::where('id_modul', $modul_data_sarpras_buku_alat->id_modul)->where('nm_menu', 'Data Buku/Alat')->first();
        //     if ($menu) {
        //         $menu->nm_menu = 'Data Alat';
        //         $menu->save();
        //     }
        //     $modul_data_sarpras_buku_alat->nm_modul = 'Data Sarpras Alat';
        //     $modul_data_sarpras_buku_alat->save();
        // }

        // $modul_data_ppsarpras = Modul::where('id_role', 10)->where('nm_modul', 'Pengadaan/Perawatan Sarpras')->first();
        // if ($modul_data_ppsarpras) {
        //     $menu = Menu::where('id_modul', $modul_data_ppsarpras->id_modul)->where('nm_menu', 'Pengadaan Barang/Sarpras')->first();
        //     if ($menu) {
        //         $menu->akses = '0';
        //         $menu->save();
        //     }

        //     $modul_data_ppsarpras->nm_modul = 'Perawatan Sarpras';
        //     $modul_data_ppsarpras->save();
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
