<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Modul;
use App\Models\Role;
use App\Models\Menu;

class CreateMenuSetJadwalKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $role = Role::where('nm_role', 'Akademik')->first();

        // $modul = Modul::where('id_role', $role->id_role)->where('nm_modul', 'Aktivitas Semester')->first();

        // $modul->menus()->where('nm_menu', 'Monitoring Kelas')->update([
        //     "urutan" => "3"
        // ]);
        // $modul->menus()->where('nm_menu', 'Plotting Mapel Siswa')->update([
        //     "urutan" => "4"
        // ]);
        // $modul->menus()->where('nm_menu', 'Hapus Plotting Mapel Siswa')->update([
        //     "urutan" => "5"
        // ]);
        // $modul->menus()->where('nm_menu', 'Cari Siswa')->update([
        //     "urutan" => "6"
        // ]);
        // $modul->menus()->where('nm_menu', 'Input Nilai')->update([
        //     "urutan" => "7"
        // ]);

        // $menu = new Menu;
        // $menu->id_modul = $modul->id_modul;
        // $menu->nm_menu = 'Set Jadwal Kelas';
        // $menu->page = 'set-jadwal-kelas';
        // $menu->urutan = 2;
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
     
    }
}
