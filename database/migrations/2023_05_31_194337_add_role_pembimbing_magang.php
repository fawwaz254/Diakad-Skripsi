<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolePembimbingMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $role = new Role;
        $role->nm_role = 'Pembimbing Magang';
        $role->deskripsi_role = 'Role Pembimbing Magang';
        $role->path = 'pembimbing-magang';
        $role->is_mobile = 0;
        $role->save();
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
