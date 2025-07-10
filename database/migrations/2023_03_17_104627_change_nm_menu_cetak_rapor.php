<?php

use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNmMenuCetakRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('id_role', '2')->where('nm_modul', 'Wali Kelas')->first();

        // if ($modul) {
        //     $modul->menus()->where('nm_menu', 'Cetak Rapor Siswa')->update([
        //         "nm_menu" => "Rapor Sisipan"
        //     ]);
        // }
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
