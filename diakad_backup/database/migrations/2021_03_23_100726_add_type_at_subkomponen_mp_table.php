<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAtSubkomponenMpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subkomponen_mp', function (Blueprint $table) {
            $table->tinyInteger('type_subkomponen_mp')->comment('0: kompetensi dasar, 1: ujian')->after('nm_subkomponen_mp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subkomponen_mp', function (Blueprint $table) {
            $table->dropColumn('type_subkomponen_mp');
       });
    }
}
