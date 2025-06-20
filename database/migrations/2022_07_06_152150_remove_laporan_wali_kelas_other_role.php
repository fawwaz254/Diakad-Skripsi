<?php

use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveLaporanWaliKelasOtherRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('id_role', 19)->where('nm_modul', 'Laporan')->first();
        // $modul->update([
        //     "akses" => 0
        // ]);
        // $modul1 = Modul::where('id_role', 6)->where('nm_modul', 'Laporan')->first();
        // $modul1->update([
        //     "akses" => 0
        // ]);
        // $modul2 = Modul::where('id_role', 10)->where('nm_modul', 'Laporan')->first();
        // $modul2->update([
        //     "akses" => 0
        // ]);
        // $modul2 = Modul::where('id_role', 14)->where('nm_modul', 'Laporan')->first();
        // $modul2->update([
        //     "akses" => 0
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
