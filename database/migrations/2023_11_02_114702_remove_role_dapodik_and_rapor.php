<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRoleDapodikAndRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::where('nm_role', 'Rapor & Buku Induk')->first();
        if ($role) {
            $role->deleted_by = 'migration';
            $role->save();
            $role->delete();
        }

        $role = Role::where('nm_role', 'Dapodik')->first();
        if ($role) {
            $role->deleted_by = 'migration';
            $role->save();
            $role->delete();
        }
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
