<?php

namespace App\Console\Commands;

use App\Models\Sekolah;
use App\Models\Pengguna;
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

        $invoiceUsers = TagihanBiaya::with('detail_biaya', 'pembayaran')
            ->whereHas('detail_biaya', function ($q) {
                $q->where('id_jenis_detail_biaya', 4);
            })
            ->whereHas('pembayaran', function ($q) use ($now) {
                $q->whereDate('created_at', $now);
            })
            ->where('is_tagih', 0)
            ->get();

        if ($invoiceUsers->isEmpty() || empty($url)) {
            return 0;
        }

        $studentsId = $invoiceUsers->pluck('id_siswa')->toArray();

        $users = Pengguna::with('siswa.wali_murid')
            ->whereHas('siswa', function ($q) use ($studentsId) {
                $q->whereIn('id_siswa', $studentsId);
            })
            ->get();

        $schoolName = Sekolah::first()->nm_sekolah;

        try {
            foreach ($users as $user) {
                $waliMurid = $user->siswa->wali_murid;

                if (!$waliMurid || empty($waliMurid->nomor_hp_wali_murid)) {
                    continue;
                }

                $data = [
                    'message' => "*Konfirmasi Pembayaran SPP*\n\n\nAssalamualaikum Wr.Wb.\nBapak/Ibu Wali Murid,\n\nKami dengan senang hati memberitahukan bahwa pembayaran SPP atas nama *" . $user->nm_pengguna . "* telah berhasil kami terima. Ketertiban Anda dalam menjalankan kewajiban ini sangat kami hargai.\n\nDengan adanya pembayaran ini, Anda telah berkontribusi dalam memastikan kelancaran proses pendidikan yang berkualitas bagi *" . $user->nm_pengguna . "*. Terima kasih sekali lagi atas dedikasi Anda dalam memastikan kelancaran pendidikan.\n\n\nSalam,\nKeuangan " . $schoolName,
                    'phone' => $waliMurid->nomor_hp_wali_murid,
                ];

                $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                $responseData = $response->json();

                if ($responseData['response'] === 'Device Bot Logged Out') {
                    \Log::info("Failed to send notification, Device bot logged out");
                } else {
                    $matchingInvoice = $invoiceUsers->firstWhere('id_siswa', $user->siswa->id_siswa);
                    if ($matchingInvoice) {
                        $matchingInvoice->notification_sent = 1;
                        $matchingInvoice->save();
                    }

                    \Log::info("Success: Notification payment sent at " . now());
                }

                sleep(2);
            }
        } catch (\Exception $e) {
            if ($e->getCode() === 0) {
                \Log::info("Error: Connection to WhatsApp Api is refused.");
            }
        }
    }
}
