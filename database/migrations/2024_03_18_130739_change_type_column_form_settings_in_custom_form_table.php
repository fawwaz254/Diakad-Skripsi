<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeColumnFormSettingsInCustomFormTable extends Migration
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
                ALTER COLUMN form_settings TYPE json 
                USING form_settings::json
            ');
            
            $table->json('form_settings')->nullable()->change();
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
