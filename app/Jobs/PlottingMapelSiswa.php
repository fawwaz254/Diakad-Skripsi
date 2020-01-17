<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\PengambilanMp;

class PlottingMapelSiswa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $pengambilan_mp_insert;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $pengambilan_mp_insert)
    {
        $this->pengambilan_mp_insert = $pengambilan_mp_insert;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        PengambilanMp::insert($this->pengambilan_mp_insert);
    }
}
