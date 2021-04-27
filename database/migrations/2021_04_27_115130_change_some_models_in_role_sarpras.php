<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSomeModelsInRoleSarpras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ruangan', function($table){
            $table->string('id_pemilik_sarpras', 40)->nullable()->change();
        });

        Schema::table('inventaris_ruangan', function (Blueprint $table) {
            $table->string('id_pemilik_sarpras', 40)->nullable()->after('id_ruangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
