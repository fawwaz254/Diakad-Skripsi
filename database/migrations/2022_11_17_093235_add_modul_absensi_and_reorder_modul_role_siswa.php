<?php

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModulAbsensiAndReorderModulRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // $role_id = Role::where('nm_role', 'Siswa')->first()->id_role;

        // $modul = Modul::create([
        //     "id_role"       => $role_id,
        //     "nm_modul"      => "Absensi",
        //     "route"         => "absensi",
        //     "urutan"        => 5,
        //     "akses"         => 1,
        //     "created_at"    => $now
        // ]);

        // $modul->menus()->createMany([
        //     [
        //         "nm_menu"      => "Histori Absensi",
        //         "page"         => "histori-absensi",
        //         "urutan"       => 1,
        //         "akses"        => 1,
        //         "created_at"   => $now
        //     ],
        // ]);



        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Biodata')->first();
        // $getModul->update([
        //     "urutan" => 1
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Data Pribadi')->first();
        // $getModul->update([
        //     "urutan" => 2
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Akademik')->first();
        // $getModul->update([
        //     "urutan" => 3
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kesiswaan')->first();
        // $getModul->update([
        //     "urutan" => 4
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Pelanggaran')->first();
        // $getModul->update([
        //     "urutan" => 6
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'E-Learning Materi')->first();
        // $getModul->update([
        //     "urutan" => 7
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'E-Learning Soal')->first();
        // $getModul->update([
        //     "urutan" => 8
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Kegiatan Harian')->first();
        // $getModul->update([
        //     "urutan" => 9
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'SKPI')->first();
        // $getModul->update([
        //     "urutan" => 10
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Dokumen Publik')->first();
        // $getModul->update([
        //     "urutan" => 11
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Keuangan')->first();
        // $getModul->update([
        //     "urutan" => 12
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Sarana Prasarana')->first();
        // $getModul->update([
        //     "urutan" => 13
        // ]);

        // $getModul = Modul::where('id_role', $role_id)->where('nm_modul', 'Bursa Kerja')->first();
        // $getModul->update([
        //     "urutan" => 14
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
