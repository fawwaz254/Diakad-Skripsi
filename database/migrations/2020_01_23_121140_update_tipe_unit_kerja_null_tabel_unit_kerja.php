<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

use App\Models\UnitKerja as UnitKerja;

class UpdateTipeUnitKerjaNullTabelUnitKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // $now = Carbon::now();

        // $unit_kerja = UnitKerja::get();

        // foreach ($unit_kerja as $uk) {
        //     // update modul
        //     $uk->tipe_unit_kerja    = null;
        //     $uk->updated_at         = $now;
        //     $uk->save();
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
