<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStufInTableSekolah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sekolah', function($table) {
            $table->integer('nss_sekolah')->nullable()->after('npsn_sekolah');
            $table->string('akreditasi',64)->nullable()->after('email_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
