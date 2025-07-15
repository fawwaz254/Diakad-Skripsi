<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventaris_ruangan', function (Blueprint $table) {
            $table->date('durasi_beli')->after('tgl_pembelian')->nullable();
            $table->string('sumber_dana', 64)->after('durasi_beli')->nullable();
            $table->string('nama_vendor', 64)->after('sumber_dana')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
