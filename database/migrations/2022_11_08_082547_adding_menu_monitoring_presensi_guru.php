<?php

use App\Models\Menu;
use App\Models\Role;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingMenuMonitoringPresensiGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $role_id = Role::where('nm_role', 'Akademik')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Monitoring')->first();

        // $modul->menus()->where('nm_menu', 'Monitoring Presensi')->update([
        //     "nm_menu" => "Monitoring Presensi Siswa",
        //     "urutan" => 3
        // ]);
        // $modul->menus()->where('nm_menu', 'Monitoring Kelas Kosong')->update([
        //     "urutan" => 4
        // ]);
        // $modul->menus()->where('nm_menu', 'Rekap Monitoring Kelas Kosong')->update([
        //     "urutan" => 5
        // ]);

        $menu = new Menu;
        $menu->id_modul = 24;
        $menu->nm_menu = 'Monitoring Presensi Guru';
        $menu->page = 'monitoring-presensi-guru';
        $menu->urutan = 2;
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
