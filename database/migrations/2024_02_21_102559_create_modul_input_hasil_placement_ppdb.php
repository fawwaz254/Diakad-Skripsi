<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Modul;
use App\Models\Role;

class CreateModulInputHasilPlacementPpdb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'PPDB')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Report')->first();

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Input Hasil Placement",
        //         "page"         => "input-hasil-placement",
        //         "urutan"       => 2,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul_input_hasil_placement_ppdb');
    }
}
