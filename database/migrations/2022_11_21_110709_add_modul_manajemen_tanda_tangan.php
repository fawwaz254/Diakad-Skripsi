<?php

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModulManajemenTandaTangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $now = Carbon::now();

        // $id_role = Role::where('id_role', 14)->first()->id_role;

        // $modul = Modul::create([
        //     "id_role"       => $id_role,
        //     "nm_modul"      => "Manajemen Tanda Tangan",
        //     "route"         => "manajemen-tanda-tangan",
        //     "urutan"        => 3,
        //     "akses"         => 1,
        //     "created_at"    => $now
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
