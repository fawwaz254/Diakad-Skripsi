<?php

use App\Models\Kelas;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAllIsInTableKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $kelas = Kelas::whereNull('is_aktif')->get();
        if ($kelas) {
            foreach ($kelas as $kelas) {
                $kelas->is_aktif = 1;
                $kelas->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
