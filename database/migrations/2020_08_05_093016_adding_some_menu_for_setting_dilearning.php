<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingSomeMenuForSettingDilearning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengampu_mapel', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_pengampu_mapel', 40)->primary();
            $table->string('id_guru', 40)->comment('FK: guru.id_guru');
            $table->string('id_mapel', 40)->comment('FK: mapel.id_mapel');
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::table('setting', function (Blueprint $table) {
            $table->string('id_sekolah', 40)->after('id_setting')->comment('FK: sekolah.id_sekolah')->nullable();
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
