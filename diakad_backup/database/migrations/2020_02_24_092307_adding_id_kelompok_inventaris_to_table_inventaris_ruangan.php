<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIdKelompokInventarisToTableInventarisRuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventaris_ruangan', function (Blueprint $table) {
            $table->string('id_kelompok_inventaris', 40)->after('id_ruangan')->nullable()->comment('FK: kelompok_inventaris.id_kelompok_inventaris');
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
