<?php

use App\Models\StatusPengguna;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditStatusPenggunaPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $status_pengguna = StatusPengguna::where('status_join_table', 1)->where('nm_status_pengguna', 'KELUAR')->first();

        $status_pengguna->nm_status_pengguna = 'KELUAR (meninggal, pensiun, mengundurkan diri)';
        $status_pengguna->save();
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
