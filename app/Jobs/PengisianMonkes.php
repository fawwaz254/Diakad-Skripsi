<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\PengisianKegiatanHarian;
use App\Models\PengisianJawaban;

class PengisianMonkes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $pengisian_kegiatan_insert;
    protected $pengisian_jawaban_insert;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $pengisian_kegiatan_insert, array $pengisian_jawaban_insert)
    {
        $this->pengisian_kegiatan_insert = $pengisian_kegiatan_insert;
        $this->pengisian_jawaban_insert = $pengisian_jawaban_insert;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        PengisianKegiatanHarian::insert($this->pengisian_kegiatan_insert);
        PengisianJawaban::insert($this->pengisian_jawaban_insert);
    }
}
