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
        Schema::table('realisasi', function (Blueprint $table) {
            $table->string('sumber_dana', 128)->nullable()->after('is_hutang_realisasi')->comment('Sumber dana untuk realisasi, berbentuk VARCHAR');
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
