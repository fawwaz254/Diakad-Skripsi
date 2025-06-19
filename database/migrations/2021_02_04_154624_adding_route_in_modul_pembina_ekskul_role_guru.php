<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddingRouteInModulPembinaEkskulRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        // $modulPembina = Modul::where('id_role', $role_id)->where('nm_modul', 'Pembina Ekskul')->first();
        // $modulPembina->route = 'pembina-ekskul';
        // $modulPembina->save();
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
