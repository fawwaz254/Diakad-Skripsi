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
        Schema::table('tutup_buku_bulanan_kas', function (Blueprint $table) {
            $table->boolean('is_fix')->default(0)->after('sisa_tunggakan_biaya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
