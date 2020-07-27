<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteKelasMpGrup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('kelas_mp_grup');

        Schema::dropIfExists('kelas_mp_grup_detail');

        Schema::dropIfExists('kelas_mp_grup_materi');

        Schema::dropIfExists('kelas_mp_grup_siswa');

        Schema::dropIfExists('kelas_mp_grup_file');
        
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
