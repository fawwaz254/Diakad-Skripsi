<?php

use App\Models\Pengguna;
use App\Models\RolePengguna;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteRoleKeuanganFromAdministrator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $pengguna = Pengguna::where('username', 'admin')->first();
        // RolePengguna::where('id_role',9)->where('id_pengguna', $pengguna->id_pengguna)->delete();

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
