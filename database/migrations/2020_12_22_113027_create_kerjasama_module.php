<?php

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKerjasamaModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $humasRoleId = Role::where('nm_role', 'Humas')->first()->id_role;

        $alumniModule = Modul::create([
            "id_role"       => $humasRoleId,
            "nm_modul"      => "Kerja Sama",
            "route"         => "kerja-sama" ,
            "urutan"        => 4,
            "akses"         => 1,
            "created_at"    => Carbon::now(env('APP_TIMEZONE', ''))
        ]);

        $alumniModule->menus()->createMany([
            [
                "nm_menu"      => "Kerja Sama",
                "page"         => "/",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => Carbon::now(env('APP_TIMEZONE', ''))
            ],
            [
                "nm_menu"      => "Jenis Kerja Sama",
                "page"         => "/jenis",
                "urutan"       => 2,
                "akses"        => 1,
                "created_at"   => Carbon::now(env('APP_TIMEZONE', ''))
            ],
            [
                "nm_menu"      => "Berkas Kerja Sama",
                "page"         => "/berkas",
                "urutan"       => 3,
                "akses"        => 1,
                "created_at"   => Carbon::now(env('APP_TIMEZONE', ''))
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
