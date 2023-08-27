<?php

namespace App\Console\Commands;

use App\Models\Sekolah;
use App\Models\Setting;
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
        $url = env('WHATSAPP_API_SEND');
        $now = now()->toDateString();

        $listPresensiPengguna = PresensiPengguna::with('pengguna.siswa.wali_murid')
            ->where([
                'status_join_table' => 3,
                'notification_sent' => 0,
                'date' => $now,
            ])->get();

        if ($listPresensiPengguna->isEmpty() || empty($url)) {
            return 0;
        }

        try {
            $namaSekolah = Sekolah::first()->nm_sekolah;

            foreach ($listPresensiPengguna as $presensiPengguna) {
                $waliMurid = $presensiPengguna->pengguna->siswa->wali_murid;

                if (!$waliMurid || empty($waliMurid->nomor_hp_wali_murid)) {
                    continue;
                }

                $template = Setting::where('key_setting', 'template_notif_kehadiran_siswa')->firstOrFail()->value;
                $template = str_replace('{{STUDENT_NAME}}', $presensiPengguna->pengguna->nm_pengguna, $template);
                $template = str_replace('{{SCHOOL_NAME}}', $namaSekolah, $template);
                $template = str_replace('{{DATE}}', \Carbon\Carbon::parse($presensiPengguna->date)->translatedFormat('l, d F Y'), $template);
                $template = str_replace('{{CHECK_IN}}', date('H:i', strtotime($presensiPengguna->check_in)), $template);
                $template = str_replace('\n', "\n", $template);

                $data = [
                    'message' => $template,
                    'phone' => $waliMurid->nomor_hp_wali_murid,
                ];

                $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                $responseData = $response->json();

                if ($responseData['response'] == 'Device Bot Logged Out') {
                    \Log::info("Warning: Failed to send notification, Device bot logged out");
                } else {
                    $presensiPengguna->notification_sent = 1;
                    $presensiPengguna->save();

                    \Log::info("Success: Notification attendance sent at " . now());
                }

                sleep(2);
            }
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                \Log::info("Error: Connection to WhatsApp Api is refused");
            } else {
                \Log::info($e);
            }
        }
    }
}
