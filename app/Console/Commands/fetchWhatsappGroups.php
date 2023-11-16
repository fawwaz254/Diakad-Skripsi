<?php

namespace App\Console\Commands;

use App\Models\WhatsappGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class fetchWhatsappGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $url = env('WHATSAPP_API_GROUPS');

        $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->post($url, ['token' => 'DSM_2023;']);
        $data = $response->json();

        return $data;
    }
}
