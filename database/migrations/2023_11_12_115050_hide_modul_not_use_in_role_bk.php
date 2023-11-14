<?php

use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideModulNotUseInRoleBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul_data_guru = Modul::where('id_role', 5)->where('nm_modul', 'Laporan Keuangan')->first();
        if ($modul_data_guru) {
            $modul_data_guru->akses = '0';
            $modul_data_guru->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
