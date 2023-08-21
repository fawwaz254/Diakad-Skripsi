<?php

namespace App\Console\Commands;

use App\Models\Pengguna;
use Illuminate\Console\Command;
use App\Models\PresensiPengguna;
use Illuminate\Support\Facades\Http;

class SendAttendanceNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Attendance Notification via WhatsApp';

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
        $url = env('WHATSAPP_API_URL');
        $now = now()->toDateString();

        $presenceUsers = PresensiPengguna::where([
            'status_join_table' => 3,
            'notification_sent' => 0,
            'date' => $now,
        ])->get();

        if ($presenceUsers->isEmpty() || empty($url)) {
            return 0;
        }

        $usersId = $presenceUsers->pluck('id_pengguna')->toArray();

        $users = Pengguna::with('siswa.wali_murid')
            ->whereIn('id_pengguna', $usersId)
            ->get();

        foreach ($users as $user) {
            $waliMurid = $user->siswa->wali_murid;

            if (!$waliMurid || empty($waliMurid->nomor_hp_wali_murid)) {
                continue;
            }

            $data = [
                'message' => "Notifikasi Kehadiran Siswa Harian\n\nHalo Bapak/Ibu wali murid!\nAnak Anda, " . $user->nm_pengguna . ", hadir di sekolah hari ini.\n\nTerima kasih!",
                'phone' => $waliMurid->nomor_hp_wali_murid,
            ];

            $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                ->post($url, $data);

            $responseData = $response->json();

            if ($responseData['response'] == 'Device Bot Logged Out') {
                \Log::info("Failed to send notification, Device bot logged out");
            } else {
                $matchingPresence = $presenceUsers->firstWhere('id_pengguna', $user->id_pengguna);
                if ($matchingPresence) {
                    $matchingPresence->notification_sent = 1;
                    $matchingPresence->save();
                }

                \Log::info("Notification sent at " . now());
            }

            sleep(2);
        }
    }
}
