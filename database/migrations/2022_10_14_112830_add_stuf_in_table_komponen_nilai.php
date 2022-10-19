<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStufInTableKomponenNilai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('komponen_nilai_rapor_sisipan', function($table) {
            $table->tinyInteger('status')->nullable()->after('urutan');
            $table->string('type', 64)->nullable()->after('urutan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('table_komponen_nilai', function (Blueprint $table) {
        //     //
        // });
    }
}
