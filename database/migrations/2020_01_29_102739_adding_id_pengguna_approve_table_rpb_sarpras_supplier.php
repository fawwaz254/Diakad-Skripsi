<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIdPenggunaApproveTableRpbSarprasSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpb_sarpras_supplier', function (Blueprint $table) {
            $table->string('id_pengguna_approve', 40)->nullable()->after('is_approve')->comment('FK: pengguna.id_pengguna, diisi ketika is_approve = 1 (yg melakukan approve supplier)');
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
