<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJenjang2InTableCalonSiswaSekolah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_sekolah', function($table) {
            $table->string('jenjang2')->nullable()->after('jenjang');
            $table->string('nm_sekolah_asal2')->nullable()->after('nm_sekolah_asal');
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
