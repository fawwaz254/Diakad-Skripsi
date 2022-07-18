<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingJmlPembayaranTahunLaluOnTutupBukuBulananBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutup_buku_bulanan_biaya', function (Blueprint $table) {
            $table->float('jml_pembayaran_biaya_tahun_lalu', 10, 0)->after('jml_pembayaran_biaya_bulan_lalu')->nullable()->comment('pembayaran dari tutup buku tahun lalu yg dilakukan di bulan ini');
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
