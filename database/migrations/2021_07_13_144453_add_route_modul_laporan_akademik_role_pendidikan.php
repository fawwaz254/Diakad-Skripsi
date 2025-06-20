<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;

class AddRouteModulLaporanAkademikRolePendidikan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        // $role_id = Role::where('nm_role', 'Pendidikan')->first()->id_role;

        // $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Laporan Akademik')->first();

        // $modul->update(['route'=>'laporan-akademik']);

        // $modul->menus()->where('nm_menu', 'Absensi Siswa')->update([
        //     "page" => "absensi-siswa"
        // ]);

        // $modul->menus()->where('nm_menu', 'Jurnal Kelas')->update([
        //     "page" => "jurnal-kelas"
        // ]);

        // $modul->menus()->where('nm_menu', 'Jurnal Guru')->update([
        //     "page" => "jurnal-guru"
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
