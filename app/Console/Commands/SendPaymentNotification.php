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

        $list_tagihan_biaya = TagihanBiaya::selectRaw('tagihan_biaya.id_tagihan_biaya, tagihan_biaya.id_siswa, tagihan_biaya.besar_pembayaran, tagihan_biaya.tgl_pelunasan, tagihan_biaya.notification_sent, pengguna.nm_pengguna, wali_murid.nomor_hp_wali_murid, CONCAT("SPP ", bulan.nm_bulan) as bulan_pembayaran')
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
            ->whereNotNull('nomor_hp_wali_murid')
            ->where('detail_biaya.id_jenis_detail_biaya', 4)
            ->where('tagihan_biaya.is_tagih', 0)
            ->where('tagihan_biaya.notification_sent', 0)
            ->orderBy('detail_biaya.id_bulan', 'asc')
            ->get();

        if ($list_tagihan_biaya->isEmpty() || empty($url)) {
            return 0;
        }

        try {
            $nama_sekolah = Sekolah::first()->nm_sekolah;

            $tagihan_to_update = [];

            foreach ($list_tagihan_biaya->groupBy('id_siswa') as $group_tagihan_biaya) {
                $nama_pengguna = '';
                $bulan_pembayaran = '';
                $nomor_hp_wali_murid = '';

                foreach ($group_tagihan_biaya as $tagihan) {
                    $nama_pengguna = $tagihan->nm_pengguna;
                    $bulan_pembayaran .= $tagihan->bulan_pembayaran . ', ';
                    $nomor_hp_wali_murid = $tagihan->nomor_hp_wali_murid;
                }

                $template = Setting::where('key_setting', 'template_notif_pembayaran_spp')->firstOrFail()->value;
                $template = str_replace('{{STUDENT_NAME}}', $nama_pengguna, $template);
                $template = str_replace('{{SCHOOL_NAME}}', $nama_sekolah, $template);
                $template = str_replace('{{PAYMENT_DATE}}', \Carbon\Carbon::parse($now)->translatedFormat('l, d F Y'), $template);
                $template = str_replace('{{PAYMENT_MONTH}}', $bulan_pembayaran, $template);
                $template = str_replace('{{PAYMENT_AMOUNT}}', number_format($group_tagihan_biaya->sum('besar_pembayaran'), '0', '', '.'), $template);
                $template = str_replace('\n', "\n", $template);

                $data = [
                    'message' => $template,
                    'phone' => $nomor_hp_wali_murid,
                ];

                $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                $response_data = $response->json();

                if ($response_data['response'] === 'Device is logged out') {
                    \Log::info("Notification Warning: Failed to send notification, Device is logged out");
                } else {
                    $tagihan_to_update = array_merge($tagihan_to_update, $group_tagihan_biaya->pluck('id_tagihan_biaya')->toArray());
                    \Log::info("Notification Success: Notification payment sent at " . now());
                }

                sleep(rand(5, 20));
            }

            if (!empty($tagihan_to_update)) {
                TagihanBiaya::whereIn('id_tagihan_biaya', $tagihan_to_update)->update(['notification_sent' => 1]);
            }
        } catch (\Exception $e) {
            \Log::info("Notification Error: " . $e->getMessage());
        }
    }
}
