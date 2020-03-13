<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;
use App\Models\Modul;

class HideModulBiodataInRoleSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now('Asia/Jakarta');
        $modul = Modul::find(12);
        $modul->akses = 0;
        $modul->updated_at = $now;
        $modul->save();
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
