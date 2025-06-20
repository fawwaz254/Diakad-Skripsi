<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\Modul;
use App\Models\Menu;
use Carbon\Carbon;

class ChangeUrutanModulMonkesRoleBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
        // $role_id = Role::where('nm_role', 'Bimbingan Konseling')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Monitoring Kesehatan')->first();
        // $modul->urutan = 3;
        // $modul->save();
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
