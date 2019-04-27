<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGelarDepanBelakangTablePengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('gelar_depan', 32)->after('status_join_table')->nullable()->comment('gelar depan dari pengguna, misal: Ir., Dr., dr., dll');
            $table->string('gelar_belakang', 32)->after('gelar_depan')->nullable()->comment('gelar belakang dari pengguna, misal: S.Kom., S.Si., S.T., dll');
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
