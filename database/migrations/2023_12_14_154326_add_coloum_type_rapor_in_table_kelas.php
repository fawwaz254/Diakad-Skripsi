<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumTypeRaporInTableKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('type_rapor', 40)->after('id_jenis_rapor')->nullable()->comment('1: Otomatis, 2: 2 Kategori, 3: Manual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('table_kelas', function (Blueprint $table) {
        //     //
        // });
    }
}
