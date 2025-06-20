<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeColumnKomponenSettingsInCustomFormKomponenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_form_komponen', function (Blueprint $table) {
            DB::statement('
                ALTER TABLE custom_form_komponen 
                ALTER COLUMN komponen_settings TYPE json 
                USING komponen_settings::json
            ');
            $table->json('komponen_settings')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_form_komponen', function (Blueprint $table) {
            //
        });
    }
}
