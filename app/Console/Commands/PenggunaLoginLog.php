<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\PenggunaLogin;
use Illuminate\Console\Command;

class PenggunaLoginLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengguna-login:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus data lama pada tabel pengguna_login';

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
        PenggunaLogin::where('login_time', '<', $tanggal_30_hari_yang_lalu)->delete();

        \Log::info("Data lama tabel pengguna_login berhasil dihapus " . date('Y-m-d H:i:s'));
    }
}
