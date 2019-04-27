<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdStaffTablePengeluaranBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengeluaran_biaya', function (Blueprint $table) {
            $table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (staff yg melakukan input pengeluaran) (null apabila guru yg melakukan input pembayaran)')->change();
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
