<?php

use App\Models\TipeSoal;
use Illuminate\Database\Migrations\Migration;

class InsertTipeSoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tipe_soal = new TipeSoal;
        $tipe_soal->nm_tipe_soal = "Pilihan Ganda";
        $tipe_soal->created_by = "D4Ka216280432986109f8223a800";
        $tipe_soal->updated_by = "D4Ka216280432986109f8223a800";
        $tipe_soal->save();

        $tipe_soal = new TipeSoal;
        $tipe_soal->nm_tipe_soal = "Essay";
        $tipe_soal->created_by = "D4Ka216280432986109f8223a800";
        $tipe_soal->updated_by = "D4Ka216280432986109f8223a800";
        $tipe_soal->save();
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
