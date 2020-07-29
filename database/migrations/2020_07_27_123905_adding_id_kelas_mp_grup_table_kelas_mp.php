<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIdKelasMpGrupTableKelasMp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas_mp', function (Blueprint $table) {
            $table->string('id_kelas_mp_grup', 40)->after('id_mata_pelajaran')->nullable()->comment('FK: kelas_mp_grup.id_kelas_mp_grup, menunjukkan bagian dari kelas_mp_grup yang mana');
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
