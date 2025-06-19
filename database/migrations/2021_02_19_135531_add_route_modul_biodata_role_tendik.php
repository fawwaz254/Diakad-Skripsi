<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddRouteModulBiodataRoleTendik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $role_id = Role::where('nm_role', 'Tenaga Pendidik')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Biodata')->first();

        // $modul->update(['route'=>'biodata']);

        // $modul->menus()->where('nm_menu', 'Data Pribadi')->update([
        //     "page" => "data-pribadi"
        // ]);
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
