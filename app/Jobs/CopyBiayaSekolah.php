<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\BiayaSekolah;
use App\Models\DetailBiaya;

class CopyBiayaSekolah implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $batch_insert_biaya_sekolah;
    protected $batch_insert_detail_biaya;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $batch_insert_biaya_sekolah, array $batch_insert_detail_biaya)
    {
        $this->batch_insert_biaya_sekolah = $batch_insert_biaya_sekolah;
        $this->batch_insert_detail_biaya = $batch_insert_detail_biaya;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        BiayaSekolah::insert($this->batch_insert_biaya_sekolah);
        DetailBiaya::insert($this->batch_insert_detail_biaya);
    }
}
