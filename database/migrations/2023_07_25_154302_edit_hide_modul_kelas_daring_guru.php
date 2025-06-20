<?php

use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditHideModulKelasDaringGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $modul = Modul::where('route','kelas-daring')->where('id_role',2)->first();
        // $modul->akses = 0;
        // $modul->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // $modul = Modul::where('route','kelas-daring')->where('id_role',2)->first();
        // $modul->akses = 1;
        // $modul->save();
    }
}
