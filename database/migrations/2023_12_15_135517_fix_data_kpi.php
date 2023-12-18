<?php

use App\Models\PointKPI;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixDataKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $point_kpi = PointKPI::where('nm_point_kpi', 'Menghapal dan mengartikan niat wudlu, niat tayamum')->first();
        if ($point_kpi) {
            $point_kpi->nm_point_kpi = 'Menghapal dan mengartikan niat wudlu niat tayamum';
            $point_kpi->save();
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
