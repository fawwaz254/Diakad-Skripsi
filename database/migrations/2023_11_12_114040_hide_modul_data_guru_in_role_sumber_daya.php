<?php

use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideModulDataGuruInRoleSumberDaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul_data_guru = Modul::where('id_role', 8)->where('nm_modul', 'Data Guru')->first();
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
    {
        // Schema::table('role_sumber_daya', function (Blueprint $table) {
        //     //
        // });
    }
}
