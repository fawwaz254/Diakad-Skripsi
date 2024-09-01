<?php

use App\Models\JenisAktivitasReward;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataJenisAktivitasReward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $data = [
            [
                "id_jenis_aktivitas_reward" => "1",
                "nm_jenis_aktivitas_reward" => "Harian",
                "created_at"                => $now
            ],
            [
                "id_jenis_aktivitas_reward" => "2",
                "nm_jenis_aktivitas_reward" => "Mingguan",
                "created_at"                => $now
            ],
            [
                "id_jenis_aktivitas_reward" => "3",
                "nm_jenis_aktivitas_reward" => "Bulanan",
                "created_at"                => $now
            ],
            [
                "id_jenis_aktivitas_reward" => "4",
                "nm_jenis_aktivitas_reward" => "Insidentil",
                "created_at"                => $now
            ],
        ];

        JenisAktivitasReward::insert($data);       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        JenisAktivitasReward::whereIn('id_jenis_aktivitas_reward', ['1', '2', '3'])->delete();
    }
}
