<?php

namespace App\Console\Commands;

use App\Models\Sekolah;
use App\Models\Pengguna;
use App\Models\Setting;
use App\Models\TagihanBiaya;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendPaymentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Payment SPP Notification via WhatsApp';

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

        $listTagihanBiaya = TagihanBiaya::with('detail_biaya', 'pembayaran', 'siswa.pengguna', 'siswa.wali_murid')
            ->whereHas('detail_biaya', function ($q) {
                $q->where('id_jenis_detail_biaya', 4);
            })
            ->whereHas('pembayaran', function ($q) use ($now) {
                $q->whereDate('created_at', $now);
            })
            ->where([
                'is_tagih' => 0,
                'notification_sent' => 0
            ])
            ->get();

        if ($listTagihanBiaya->isEmpty() || empty($url)) {
            return 0;
        }

        try {
            $namaSekolah = Sekolah::first()->nm_sekolah;
            $template = Setting::where('key_setting', 'template_notif_pembayaran_spp')->firstOrFail()->value;

            foreach ($listTagihanBiaya as $tagihanBiaya) {
                $waliMurid = $tagihanBiaya->siswa->wali_murid;

                if (!$waliMurid || empty($waliMurid->nomor_hp_wali_murid)) {
                    continue;
                }

                $template = str_replace('{{STUDENT_NAME}}', $tagihanBiaya->siswa->pengguna->nm_pengguna, $template);
                $template = str_replace('{{SCHOOL_NAME}}', $namaSekolah, $template);
                $template = str_replace('{{PAYMENT_DATE}}', $tagihanBiaya->tgl_pelunasan, $template);
                $template = str_replace('{{PAYMENT_AMOUNT}}', $tagihanBiaya->besar_pembayaran, $template);
                $template = str_replace('\n', "\n", $template);

                $data = [
                    'message' => $template,
                    'phone' => $waliMurid->nomor_hp_wali_murid,
                ];

                $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                $responseData = $response->json();

                if ($responseData['response'] === 'Device Bot Logged Out') {
                    \Log::info("Warning: Failed to send notification, Device bot logged out");
                } else {
                    $tagihanBiaya->notification_sent = 1;
                    $tagihanBiaya->save();

                    \Log::info("Success: Notification payment sent at " . now());
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
