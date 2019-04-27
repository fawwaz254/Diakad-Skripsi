<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRangkumanHomeVisitTableHomeVisit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_visit', function (Blueprint $table) {
            $table->string('rangkuman_home_visit', 256)->after('alamat_wali_murid')->nullable()->comment('diisi rangkuman hasil home visit');
        });
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
