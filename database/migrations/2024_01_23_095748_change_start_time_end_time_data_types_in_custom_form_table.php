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
            DB::statement('
                ALTER TABLE custom_form 
                ALTER COLUMN start_time TYPE timestamp(0) without time zone 
                USING (current_date + start_time)::timestamp(0)
            ');
            
            DB::statement('
                ALTER TABLE custom_form 
                ALTER COLUMN end_time TYPE timestamp(0) without time zone 
                USING (current_date + end_time)::timestamp(0)
            ');
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
