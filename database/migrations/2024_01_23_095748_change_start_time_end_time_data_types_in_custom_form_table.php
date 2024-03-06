<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStartTimeEndTimeDataTypesInCustomFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_form', function (Blueprint $table) {
            $table->dateTime('start_time')->change();
            $table->dateTime('end_time')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_form', function (Blueprint $table) {
            //
        });
    }
}
