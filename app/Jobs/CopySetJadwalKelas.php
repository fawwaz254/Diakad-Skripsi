<?php

namespace App\Jobs;

use App\Models\JadwalKelasMp;
use App\Models\KelasMp;
use App\Models\PengampuMp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CopySetJadwalKelas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $batch_insert_kelas_mp;
    protected $batch_insert_jadwal_kelas_mp;
    protected $batch_insert_pengampu_mp;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $batch_insert_kelas_mp, array $batch_insert_jadwal_kelas_mp, array $batch_insert_pengampu_mp)
    {
        $this->batch_insert_kelas_mp = $batch_insert_kelas_mp;
        $this->batch_insert_jadwal_kelas_mp = $batch_insert_jadwal_kelas_mp;
        $this->batch_insert_pengampu_mp = $batch_insert_pengampu_mp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (sizeof($this->batch_insert_kelas_mp) > 0) {
            KelasMp::insert($this->batch_insert_kelas_mp);
        }
        if (sizeof($this->batch_insert_jadwal_kelas_mp) > 0) {
            JadwalKelasMp::insert($this->batch_insert_jadwal_kelas_mp);
        }
        if (sizeof($this->batch_insert_pengampu_mp) > 0) {
            PengampuMp::insert($this->batch_insert_pengampu_mp);
        }
    }
}
