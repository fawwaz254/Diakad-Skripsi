<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\PengambilanEkskul;

class CopySettingPengambilanEkskul implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch_insert_pengambilan_ekskul;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $batch_insert_pengambilan_ekskul)
    {
        $this->batch_insert_pengambilan_ekskul = $batch_insert_pengambilan_ekskul;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (sizeof($this->batch_insert_pengambilan_ekskul) > 0) {
            PengambilanEkskul::insert($this->batch_insert_pengambilan_ekskul);
        }
    }
}
