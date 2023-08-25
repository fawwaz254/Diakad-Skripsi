<?php

namespace App\Console\Commands;

use App\Models\Sekolah;
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

        $listTagihanBiaya = TagihanBiaya::selectRaw('tagihan_biaya.id_tagihan_biaya, tagihan_biaya.id_siswa, tagihan_biaya.besar_pembayaran, tagihan_biaya.tgl_pelunasan, tagihan_biaya.notification_sent, pengguna.nm_pengguna, wali_murid.nomor_hp_wali_murid, CONCAT("SPP ", bulan.nm_bulan) as bulan_pembayaran')
            ->join('detail_biaya', 'detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
            ->join('bulan', 'bulan.id_bulan', '=', 'detail_biaya.id_bulan')
            ->leftJoin('pembayaran_biaya', function ($q) {
                $q->on('pembayaran_biaya.id_tagihan_biaya', '=', 'tagihan_biaya.id_tagihan_biaya')
                    ->whereNull('pembayaran_biaya.deleted_at');
            })
            ->join('siswa', 'siswa.id_siswa', '=', 'tagihan_biaya.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->leftJoin('wali_murid', 'wali_murid.id_wali_murid', '=', 'siswa.id_wali_murid')
            ->whereDate('pembayaran_biaya.tgl_pembayaran', $now)
            ->where('detail_biaya.id_jenis_detail_biaya', 4)
            ->where('tagihan_biaya.is_tagih', 0)
            ->where('tagihan_biaya.notification_sent', 0)
            ->orderBy('detail_biaya.id_bulan', 'asc')
            ->get();

        if ($listTagihanBiaya->isEmpty() || empty($url)) {
            return 0;
        }

        try {
            $namaSekolah = Sekolah::first()->nm_sekolah;

            $tagihanToUpdate = [];

            foreach ($listTagihanBiaya->groupBy('id_siswa') as $groupTagihanBiaya) {
                $bulanPembayaran = '';
                $nomorHpWaliMurid = '';
                $namaPengguna = '';

                foreach ($groupTagihanBiaya as $tagihan) {
                    $bulanPembayaran .= $tagihan->bulan_pembayaran . ', ';
                    $nomorHpWaliMurid = $tagihan->nomor_hp_wali_murid;
                    $namaPengguna = $tagihan->nm_pengguna;
                }

                if (empty($nomorHpWaliMurid)) {
                    continue;
                }

                $template = Setting::where('key_setting', 'template_notif_pembayaran_spp')->firstOrFail()->value;
                $template = str_replace('{{STUDENT_NAME}}', $namaPengguna, $template);
                $template = str_replace('{{SCHOOL_NAME}}', $namaSekolah, $template);
                $template = str_replace('{{PAYMENT_DATE}}', \Carbon\Carbon::parse($now)->translatedFormat('l, d F Y'), $template);
                $template = str_replace('{{PAYMENT_MONTH}}', $bulanPembayaran, $template);
                $template = str_replace('{{PAYMENT_AMOUNT}}', number_format($groupTagihanBiaya->sum('besar_pembayaran'), '0', '', '.'), $template);
                $template = str_replace('\n', "\n", $template);

                $data = [
                    'message' => $template,
                    'phone' => $nomorHpWaliMurid,
                ];

                $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                $responseData = $response->json();

                if ($responseData['response'] === 'Device Bot Logged Out') {
                    \Log::info("Warning: Failed to send notification, Device bot logged out");
                } else {
                    $tagihanToUpdate = array_merge($tagihanToUpdate, $groupTagihanBiaya->pluck('id_tagihan_biaya')->toArray());
                    \Log::info("Success: Notification payment sent at " . now());
                }

                sleep(2);
            }

            if (!empty($tagihanToUpdate)) {
                TagihanBiaya::whereIn('id_tagihan_biaya', $tagihanToUpdate)->update(['notification_sent' => 1]);
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
