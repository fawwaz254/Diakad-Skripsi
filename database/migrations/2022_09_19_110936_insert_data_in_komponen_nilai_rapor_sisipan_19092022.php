<?php

use App\Models\KomponenNilaiRaporSisipan;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// use DB;
use Carbon\Carbon;

class InsertDataInKomponenNilaiRaporSisipan19092022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
        $prefix = Sekolah::first()->prefix;
        $data = array(
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI FORMATIF 1',
                'urutan' => 1,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI FORMATIF 2',
                'urutan' => 2,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI FORMATIF 3',
                'urutan' => 3,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI FORMATIF 4',
                'urutan' => 4,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI SUMATIF 1',
                'urutan' => 5,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI SUMATIF 2',
                'urutan' => 6,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI SUMATIF 3',
                'urutan' => 7,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'NILAI SUMATIF 4',
                'urutan' => 8,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'STS',
                'urutan' => 9,
                'created_at' => $now
            ),
            array(
                'id_komponen_nilai' => $prefix . strtotime($now) . uniqid(),
                'nm_nilai' => 'SAS',
                'urutan' => 10,
                'created_at' => $now
            )
        );
        KomponenNilaiRaporSisipan::insert($data);
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
