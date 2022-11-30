<?php

namespace App\Jobs;

use App\Models\PresensiPengguna;
use App\Models\ShiftPengguna;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobShiftPengguna implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $list_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $list_data)
    {
        $this->list_data = $list_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ShiftPengguna::insert($this->list_data);
    }
}
