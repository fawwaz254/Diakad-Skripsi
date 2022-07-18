<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdStaffBayarTablePembayaranBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran_biaya', function (Blueprint $table) {
            $table->string('id_staff_bayar', 40)->nullable()->comment('FK: staff.id_staff (null apabila guru yg melakukan input pembayaran)')->change();
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
