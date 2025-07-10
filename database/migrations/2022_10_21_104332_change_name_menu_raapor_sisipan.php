<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Modul;
use App\Models\Role;

class ChangeNameMenuRaaporSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // $modul = Modul::where('id_role', 2)->where('nm_modul', 'Rapor Sisipan')->first();

        // $modul->menus()->where('nm_menu', 'Daftar Nilai STS')->update([
        //     "nm_menu" => "Daftar Nilai UTS"
        // ]);
        // $modul->menus()->where('nm_menu', 'Daftar Nilai SAS')->update([
        //     "nm_menu" => "Daftar Nilai UAS"
        // ]);

        // $modul2 = Modul::where('id_role', 7)->where('nm_modul', 'Rapor Sisipan')->first();
        // $modul2->menus()->where('nm_menu', 'Daftar Nilai STS')->update([
        //     "nm_menu" => "Daftar Nilai UTS"
        // ]);
        // $modul2->menus()->where('nm_menu', 'Daftar Nilai SAS')->update([
        //     "nm_menu" => "Daftar Nilai UAS"
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
