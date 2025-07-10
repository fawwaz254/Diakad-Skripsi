<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use App\Models\Modul;
use App\Models\Role;

use Carbon\Carbon;

class RefactoringRouteForAlumniKerjasama extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Humas')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Alumni')->first();

        // $modul->menus()->where('nm_menu', 'Tracer Alumni')->update([
        //     "page"         => "tracer-alumni"
        // ]);

        // $modul->menus()->where('nm_menu', 'Tambah Alumni')->update([
        //     "page"         => "tambah-alumni"
        // ]);

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kerja Sama')->first();

        // $modul->menus()->where('nm_menu', 'Kerja Sama')->update([
        //     "nm_menu"   => "Data Kerja Sama",
        //     "page"      => "list"
        // ]);

        // $modul->menus()->where('nm_menu', 'Jenis Kerja Sama')->update([
        //     "page"         => "jenis"
        // ]);

        // $modul->menus()->where('nm_menu', 'Berkas Kerja Sama')->update([
        //     "page"         => "berkas"
        // ]);

        // $modul->menus()->where('nm_menu', 'Instansi')->update([
        //     "page"         => "instansi"
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
