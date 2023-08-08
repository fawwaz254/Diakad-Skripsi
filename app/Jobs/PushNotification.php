<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\administrator\Notification\FcmNotification;
use App\Models\NotifikasiPengguna;
use App\Models\Sekolah;
use Carbon\Carbon;

class PushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $p;
    protected $title;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($p, $title, $body)
    {
        $this->p = $p;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sekolah = Sekolah::first();
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $notifikasi = new NotifikasiPengguna;
        $notifikasi->id_notifikasi_pengguna =  $sekolah->prefix . strtotime($now) . uniqid();
        $notifikasi->id_pengguna = $this->p->id_pengguna;
        $notifikasi->id_sekolah = $sekolah->id_sekolah;
        $notifikasi->isi_notifikasi = $this->body;
        $notifikasi->status = 1;
        $notifikasi->save();

        $this->p->notify((new FcmNotification)->with($this->title, $this->body));
    }
}
