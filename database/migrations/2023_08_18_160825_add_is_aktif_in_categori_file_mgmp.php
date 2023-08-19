<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAktifInCategoriFileMgmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_file_mgmp', function (Blueprint $table) {
            $table->boolean('is_aktif')->after('category_file_explanation')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
