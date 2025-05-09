<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMenusOnKunjunganMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $Menus = Menu::where('nm_menu', 'Magang siswa')->get();

        $Moduls = Modul::where('nm_modul', 'Magang Siswa')
            ->whereIn('id_role', [2, 19])
            ->get();

        $menus = [
            2 => [
                'nm_menu' => 'Kunjungan magang',
                'page' => 'add-kunjungan-magang',
                'urutan' => 5,
                'akses' => 1,
            ],
            19 => [
                'nm_menu' => 'Kunjungan magang',
                'page' => 'kunjungan-magang',
                'urutan' => 6,
                'akses' => 1,
            ]
        ];

        foreach ($Menus as $menu) {
            echo "Hapus menu" . "\n";
            $menu->forceDelete();
            echo "Selesai delete menu" . "\n";
        }

        foreach ($Moduls as $modul) {
            if ($menus[$modul->id_role]) {
                $menuData = $menus[$modul->id_role];

                $menu = new Menu();
                $menu->id_modul = $modul->id_modul;
                $menu->nm_menu = $menuData['nm_menu'];
                $menu->page = $menuData['page'];
                $menu->urutan = $menuData['urutan'];
                $menu->akses = $menuData['akses'];
                $menu->save();

                echo "Menambahkan menu: " . $menu->page . " untuk role " . $modul->id_role . "\n";
            } else {
                echo "Tidak ada data menu untuk role " . $modul->id_role . "\n";
            }
        }

        echo "Semua menu 'Magang Siswa' telah diperbarui\n";
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
