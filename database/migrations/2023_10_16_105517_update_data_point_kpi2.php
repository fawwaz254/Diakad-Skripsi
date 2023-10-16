<?php

use App\Models\PointKPI;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataPointKpi2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $point_kpi1 = PointKPI::where('nm_point_kpi', "Pengulangan materi smt 1 & 2")->first();
        if ($point_kpi1) {
            $point_kpi1->nm_point_kpi = "Pengulangan materi smt 1 dan 2";
            $point_kpi1->save();
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
