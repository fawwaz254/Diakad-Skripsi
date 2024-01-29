<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class AddingMenuUploadDokumenOnRoleGuruTendik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $role_id = Role::where('nm_role', 'Guru')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kesekretariatan')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Upload Dokumen",
                "page"         => "upload-dokumen",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);

        $role_id = Role::where('nm_role', 'Pelatih Ekskul')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kesekretariatan')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Upload Dokumen",
                "page"         => "upload-dokumen",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);

        $role_id = Role::where('nm_role', 'Tenaga Pendidik')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kesekretariatan')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Upload Dokumen",
                "page"         => "upload-dokumen",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);
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
