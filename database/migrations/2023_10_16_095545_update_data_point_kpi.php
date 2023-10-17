<?php

use App\Models\PointKPI;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataPointKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $point_kpi1 = PointKPI::where('nm_point_kpi', "An - Nashr ( Q.S 110 )")->first();
        if ($point_kpi1) {
            $point_kpi1->nm_point_kpi = "An-Nashr (Q.S 110)";
            $point_kpi1->save();
        }

        $point_kpi2 = PointKPI::where('nm_point_kpi', "Al Lahab ( Q.S. 111 )")->first();
        if ($point_kpi2) {
            $point_kpi2->nm_point_kpi = "Al Lahab (Q.S. 111)";
            $point_kpi2->save();
        }

        $point_kpi3 = PointKPI::where('nm_point_kpi', "Al Kafirun ( Q.S. 109 )")->first();
        if ($point_kpi3) {
            $point_kpi3->nm_point_kpi = "Al Kafirun (Q.S. 109)";
            $point_kpi3->save();
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
