<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteDuplicateModulAbsensi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $jumlah =  Modul::where('id_role', 2)->where('nm_modul', 'Absensi')->count();
        // if ($jumlah == '2') {
        //     $modul_absensi = Modul::where('id_role', 2)->where('nm_modul', 'Absensi')->get();
        //     foreach ($modul_absensi as $modul) {
        //         $menu = Menu::where('id_modul', $modul->id_modul)->count();
        //         if ($menu == '1') {
        //             $modul->deleted_by = 'migration';
        //             $modul->save();
        //             $modul->delete();
        //         }
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
