<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\KelasMp;

class CopyUsulanMataAjar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch_insert_kelas_mp;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $batch_insert_kelas_mp)
    {
        $this->batch_insert_kelas_mp = $batch_insert_kelas_mp;
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
    }
}
