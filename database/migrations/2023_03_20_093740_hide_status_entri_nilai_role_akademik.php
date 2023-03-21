<?php

use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideStatusEntriNilaiRoleAkademik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('id_role', '7')->where('nm_modul', 'Monitoring')->first();

        if ($modul) {
            $modul->menus()->where('nm_menu', 'Status Entri Nilai')->update([
                "akses" => "0"
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
