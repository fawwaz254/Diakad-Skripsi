<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingTglApproveTableRpbSarprasSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpb_sarpras_supplier', function (Blueprint $table) {
            $table->date('tgl_approve')->nullable()->after('id_pengguna_approve')->comment('tanggal saat approve supplier');
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
