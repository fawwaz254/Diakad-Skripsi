<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

use App\Models\Modul as Modul;
use App\Models\Menu as Menu;

class AddDbEditModulPengadaanSarprasRoleSarpras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $now = Carbon::now();

        // update modul
        // $modul               = Modul::find(68);
        // $modul->nm_modul     = "Pengadaan/Perawatan Sarpras";
        // $modul->updated_at   = $now;
        // $modul->save();
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
