<?php

use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameMenuUsulanMataAjarAndPlottingMapelSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('id_role', '7')->where('nm_modul', 'Aktivitas Semester')->first();

        if ($modul) {
            $modul->menus()->where('nm_menu', 'Usulan Mata Ajar')->update([
                "nm_menu" => "View Jadwal Kelas",
                "page" => "view-jadwal-kelas"
            ]);

            $modul->menus()->where('nm_menu', 'Plotting Mapel Siswa')->update([
                "nm_menu" => "Total Jadwal Kelas",
                "page" => "total-jadwal-kelas"
            ]);
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
