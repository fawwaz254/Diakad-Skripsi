<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenusOnKunjunganMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduls = Modul::where('nm_modul', 'Magang Siswa')->whereIn('id_role', [2, 19])->get();
        // guru == add
        // humas == view
        $menus = [
            [
                'nm_menu' => 'Magang siswa',
                'page' => 'add-magang-siswa',
                'urutan' => 5,
                'akses' => 1,
            ],
            [
                'nm_menu' => 'Magang siswa',
                'page' => 'view-magang-siswa',
                'urutan' => 6,
                'akses' => 1,
            ]
        ];

        if ($moduls) {
            foreach ($moduls as $modul) {
                foreach ($menus as $men) {
                    $menu = new Menu();
                    $menu->id_modul = $modul->id_modul;
                    $menu->nm_menu = $men['nm_menu'];
                    $menu->page = $men['page'];
                    $menu->urutan = $men['urutan'];
                    $menu->akses = $men['akses'];
                    $menu->save();
                }
            }
        } else {
            throw new \Exception("Modul 'Magang Siswa' untuk role 2 atau 19 tidak ditemukan");
        }
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
