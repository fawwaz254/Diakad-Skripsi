<?php

use App\Models\KelompokMapelRapor;
use App\Models\Rapor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataInNullNmRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rapor = Rapor::whereNull('nm_rapor')->get();
        foreach ($rapor as $r) {
            $r->nm_rapor = 'semester';
            $r->save();
        }

        $kelompok_mapel_rapor = KelompokMapelRapor::whereNull('nm_rapor')->get();
        foreach ($kelompok_mapel_rapor as $k) {
            $k->nm_rapor = 'semester';
            $k->save();
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
