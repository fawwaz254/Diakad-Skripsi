<?php

use App\Models\Role;
use App\Models\RolePengguna;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDeletedRoleInRolePengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $removed_role = Role::withTrashed()->whereNotNull('deleted_at')->get();
        foreach ($removed_role as $role) {
            $role_penggunas = RolePengguna::where('id_role', $role->id_role)->get();
            foreach ($role_penggunas as $role_pengguna) {
                $role_pengguna->deleted_by = 'migration';
                $role_pengguna->save();
                $role_pengguna->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
