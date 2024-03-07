<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTglKegiatanColumnInKegiatanGuruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatan_guru', function (Blueprint $table) {
            $table->renameColumn('tgl_kegiatan', 'tgl_mulai_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kegiatan_guru', function (Blueprint $table) {
            //
        });
    }
}
