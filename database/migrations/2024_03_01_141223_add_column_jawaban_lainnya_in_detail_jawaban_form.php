<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnJawabanLainnyaInDetailJawabanForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_jawaban_form', function (Blueprint $table) {
            $table->string('jawaban_lainnya')->nullable()->after('jawaban');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_jawaban_form', function (Blueprint $table) {
            //
        });
    }
}
