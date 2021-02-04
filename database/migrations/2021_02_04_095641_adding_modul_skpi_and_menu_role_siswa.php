<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;


class AddingModulSkpiAndMenuRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Siswa')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "SKPI",
            "route"         => "skpi" ,
            "urutan"        => 1,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Data Kegiatan Siswa",
                "page"         => "data-kegiatan-siswa",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
            [
                "nm_menu"      => "Data Prestasi Siswa",
                "page"         => "data-prestasi-siswa",
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
