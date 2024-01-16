<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdCustomFormSheetInCustomFormResponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_form_respon', function (Blueprint $table) {
            $table->string('id_custom_form_sheet', 40)->nullable()->after('id_custom_form_respon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_form_respon', function (Blueprint $table) {
            //
        });
    }
}
