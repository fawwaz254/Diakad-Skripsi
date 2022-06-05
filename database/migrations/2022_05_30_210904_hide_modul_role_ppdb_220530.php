<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Menu;
use App\Models\Role;
use Carbon\Carbon;

class HideModulRolePpdb220530 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role_id = Role::where('nm_role', 'PPDB')->first()->id_role;
        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Penetapan')->first();

        $modul->akses = 0;
        $modul->save();
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
