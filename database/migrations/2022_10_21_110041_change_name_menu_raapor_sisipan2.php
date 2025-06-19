<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Modul;
use App\Models\Role;

class ChangeNameMenuRaaporSisipan2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('id_role', 2)->where('nm_modul', 'Rapor Sisipan')->first();

        // $modul->menus()->where('nm_menu', 'Daftar Nilai UTS')->update([
        //     "nm_menu" => "Daftar Nilai Tengah Semester"
        // ]);
        // $modul->menus()->where('nm_menu', 'Daftar Nilai UAS')->update([
        //     "nm_menu" => "Daftar Nilai Akhir Semester"
        // ]);

        // $modul2 = Modul::where('id_role', 7)->where('nm_modul', 'Rapor Sisipan')->first();
        // $modul2->menus()->where('nm_menu', 'Daftar Nilai UTS')->update([
        //     "nm_menu" => "Daftar Nilai Tengah Semester"
        // ]);
        // $modul2->menus()->where('nm_menu', 'Daftar Nilai UAS')->update([
        //     "nm_menu" => "Daftar Nilai Akhir Semester"
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
