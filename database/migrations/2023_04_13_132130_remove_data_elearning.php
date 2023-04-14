<?php

use App\Models\DetailPaketSoal;
use App\Models\JawabanTest;
use App\Models\PaketSoal;
use App\Models\Test;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDataElearning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $paket_soals = PaketSoal::get();
        if ($paket_soals) {
            foreach ($paket_soals as $paket_soal) {
                $paket_soal->delete();
            }
        }

        $detail_paket_soals = DetailPaketSoal::get();
        if ($detail_paket_soals) {
            foreach ($detail_paket_soals as $detail_paket_soal) {
                $detail_paket_soal->delete();
            }
        }

        $tests = Test::get();
        if ($tests) {
            foreach ($tests as $test) {
                $test->delete();
            }
        }

        $jawaban_tests = JawabanTest::get();
        if ($jawaban_tests) {
            foreach ($jawaban_tests as $jawaban_test) {
                $jawaban_test->delete();
            }
        }
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
