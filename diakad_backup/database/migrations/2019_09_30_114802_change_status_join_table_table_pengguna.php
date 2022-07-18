<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusJoinTableTablePengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->boolean('status_join_table')->after('must_change_password')->nullable()->comment('1 = Pegawai; 2 = Guru; 3 = Siswa; 4 = Wali Murid; 5 = Pelatih Ekskul; 6 = Supplier;')->change();
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
