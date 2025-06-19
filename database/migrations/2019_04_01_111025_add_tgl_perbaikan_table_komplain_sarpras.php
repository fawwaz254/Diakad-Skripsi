<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTglPerbaikanTableKomplainSarpras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('komplain_sarpras', function (Blueprint $table) {
            $table->timestamp('tgl_perbaikan')->after('id_guru_sarpras')->nullable()->comment('tgl ketika sarpras melakukan perbaikan');
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
