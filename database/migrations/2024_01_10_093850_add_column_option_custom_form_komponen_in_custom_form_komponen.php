<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOptionCustomFormKomponenInCustomFormKomponen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_form_komponen', function (Blueprint $table) {
            $table->addColumn('text', 'option_custom_form_komponen')->nullable()->after('tipe_custom_form_komponen');
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
