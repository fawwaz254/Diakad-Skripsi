<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\LogSesiPengguna;
use Illuminate\Console\Command;

class ClearSesiPengguna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sesi-pengguna:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus data > 30 hari terakhir pada tabel log_sesi_pengguna';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tanggal_30_hari_yang_lalu = Carbon::now()->subDays(30);
        LogSesiPengguna::where('login_time', '<', $tanggal_30_hari_yang_lalu)->delete();

        \Log::info("Data log_sesi_pengguna > 30 hari terakhir berhasil dihapus " . date('Y-m-d H:i:s'));
    }
}
