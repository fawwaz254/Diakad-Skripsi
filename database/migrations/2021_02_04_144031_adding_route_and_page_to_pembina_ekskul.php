<?php

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingRouteAndPageToPembinaEkskul extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        $modulPenilaian = Modul::where('id_role', $role_id)->where('nm_modul', 'Penilaian')->first();

        $modulPenilaian->menus()->where('nm_menu', 'Rekap Nilai')->update([
            "page" => "rekap-nilai"
        ]);

        $modulPembina = Modul::where('id_role', $role_id)->where('nm_modul', 'Pembina Ekskul')->first();

        $modulPembina->menus()->where('nm_menu', 'Rekap Absensi Ekskul')->update([
            "page" => "rekap-absensi-ekskul"
        ]);
        $modulPembina->menus()->where('nm_menu', 'Input Nilai Ekskul')->update([
            "page" => "input-nilai-ekskul"
        ]);
        $modulPembina->menus()->where('nm_menu', 'Rekap Nilai Ekskul')->update([
            "page" => "rekap-nilai-ekskul"
        ]);
        $modulPembina->menus()->where('nm_menu', 'Komponen Nilai Ekskul')->update([
            "page" => "komponen-nilai-ekskul"
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
