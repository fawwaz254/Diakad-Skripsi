<?php

use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideHapusPlottingMapelSiswaRoleAkademik extends Migration
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
            $modul->menus()->where('nm_menu', 'Hapus Plotting Mapel Siswa')->update([
                "akses" => 0
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
