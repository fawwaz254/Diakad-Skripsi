<?php

use App\Models\Pengguna;
use App\Models\RolePengguna;
use App\Models\WaliMurid;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteWaliMuridCorrup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        set_time_limit(-1);
        $wali_murids = WaliMurid::with('siswa')->whereDoesntHave('siswa')->withTrashed()->get();

        foreach ($wali_murids as $wali_murid) {
            $pengguna = Pengguna::where('id_pengguna', $wali_murid->id_pengguna)->first();
            if ($pengguna) {
                $pengguna->deleted_by = "batch delete migration";
                $pengguna->save();
                $pengguna->delete();
            }
            $role_p = RolePengguna::where('id_pengguna', $wali_murid->id_pengguna)->first();
            if ($role_p) {
                $role_p->deleted_by = "batch delete ";
                $role_p->save();
                $role_p->delete();
            }

            $wali = WaliMurid::where('id_wali_murid', $wali_murid->id_wali_murid)->first();
            if ($wali) {
                $wali->deleted_by = "batch delete migration";
                $wali->save();
                $wali->delete();
            }
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
