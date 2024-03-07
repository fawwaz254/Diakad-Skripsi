<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOthersInPertanyaanForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pertanyaan_form', function (Blueprint $table) {
            $table->tinyInteger('others')->default(0)->comment('0: Tidak, 1: Ya');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pertanyaan_form', function (Blueprint $table) {
            //
        });
    }
}
