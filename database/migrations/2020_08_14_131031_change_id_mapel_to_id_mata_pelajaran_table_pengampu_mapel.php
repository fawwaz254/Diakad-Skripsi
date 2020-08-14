<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdMapelToIdMataPelajaranTablePengampuMapel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengampu_mapel', function (Blueprint $table) {
            $table->string('id_mata_pelajaran', 40)->after('id_guru')->comment('FK: mata_pelajaran.id_mata_pelajaran');
            $table->dropColumn('id_mapel');
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
