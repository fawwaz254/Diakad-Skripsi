<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingColumnOnTablePembayaranTransaksiTagihanBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran_transaksi', function (Blueprint $table) {
            $table->string('payment_channel', 64)->after('token')->nullable();
            $table->string('payment_code', 64)->after('payment_channel')->nullable();
            $table->decimal('fee_admin', 10, 2)->after('payment_code')->nullable();
        });

        Schema::table('tagihan_biaya', function (Blueprint $table) {
            $table->boolean('is_request')->after('is_tagih')->comment('0 = dapat direquest; 1 = tidak bisa request');
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
