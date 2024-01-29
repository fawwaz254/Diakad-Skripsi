<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddingPageOnMenuCetakLaporanKeuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Keuangan')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Laporan Keuangan')->first();

        $modul->menus()->where('nm_menu', 'Cetak Laporan')->update([
            "page" => "cetak-laporan"
        ]);
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
