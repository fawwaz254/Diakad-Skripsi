<?php

namespace App\Console\Commands;

use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Setting;
use App\Models\TagihanBiaya;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendPaymentNotificationByClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:payment-class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Payment SPP Notification via WhatsApp by class';

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
        $id_group_admin = Setting::where('key_setting', 'id_group_whatsapp_admin')->value('value');
        $url = env('WHATSAPP_API_SEND');

        if (empty($url)) {
            \Log::info("Notification Warning: Failed to send notification, API URL not found");
            return;
        }

        $now = now()->toDateString();

        $list_kelas = Kelas::has('whatsapp_group')->with('whatsapp_group')->get();
        $kelas = [];
        foreach ($list_kelas as $item) {
            $nm_kelas = $item->nm_kelas;
            $id_group = $item->whatsapp_group->id_group;
            $kelas[$nm_kelas] = $id_group;
        }

        $list_tagihan_biaya = TagihanBiaya::selectRaw('tagihan_biaya.id_tagihan_biaya, tagihan_biaya.id_siswa, tagihan_biaya.besar_pembayaran, tagihan_biaya.tgl_pelunasan, tagihan_biaya.notification_sent, pengguna.nm_pengguna, CONCAT("SPP ", bulan.nm_bulan) as bulan_pembayaran')
            ->join('detail_biaya', 'detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
            ->join('bulan', 'bulan.id_bulan', '=', 'detail_biaya.id_bulan')
            ->leftJoin('pembayaran_biaya', function ($q) {
                $q->on('pembayaran_biaya.id_tagihan_biaya', '=', 'tagihan_biaya.id_tagihan_biaya')
                    ->whereNull('pembayaran_biaya.deleted_at');
            })
            ->join('siswa', 'siswa.id_siswa', '=', 'tagihan_biaya.id_siswa')
            ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->whereDate('pembayaran_biaya.tgl_pembayaran', $now)
            ->where('detail_biaya.id_jenis_detail_biaya', 4)
            ->where('tagihan_biaya.is_tagih', 0)
            ->where('tagihan_biaya.notification_sent', 0)
            ->orderBy('detail_biaya.id_bulan', 'asc')
            ->get()
            ->groupBy('siswa.kelas.nm_kelas');

        if ($list_tagihan_biaya->isEmpty()) {
            \Log::info("Notification Warning: Failed to send notification, there is no payment for today");
            return;
        }

        try {
            $nama_sekolah = Sekolah::first()->nm_sekolah;
            $base_template = Setting::where('key_setting', 'template_notif_pembayaran_spp')->value('value');

            $tagihan_to_update = [];

            foreach ($list_tagihan_biaya as $key => $tagihan_biaya) {
                if (!isset($kelas[$key])) {
                    continue;
                }

                $group_tagihan_biaya = $tagihan_biaya->groupBy('id_siswa');

                $siswa_kelas = [];

                foreach ($group_tagihan_biaya as $tagihan) {

                    $nama_pengguna = '';
                    $bulan_pembayaran = '';

                    foreach ($tagihan as $t) {

                        $nama_pengguna = $t->nm_pengguna;
                        $bulan_pembayaran .= $t->bulan_pembayaran . ', ';
                    }

                    $siswa_kelas[] = $nama_pengguna . " ( " . $bulan_pembayaran . ")";
                }

                $content_message = join("\n---------------------------------------------------------------------------------- \n", $siswa_kelas);

                $template = $base_template;
                $message = str_replace(
                    ['{{CLASS}}', '{{DATE}}', '{{SCHOOL}}', '{{STUDENTS}}', '\n'],
                    [$key, now()->translatedFormat('l, d F Y'), $nama_sekolah, $content_message, "\n"],
                    $template
                );

                $data = [
                    'message' => $message,
                    'group_id' => $kelas[$key],
                ];

                $response1 = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                if ($id_group_admin !== '') {
                    sleep(10);
                    $response2 = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                        ->post($url, [
                            'message' => $message,
                            'group_id' => $id_group_admin,
                        ]);
                }

                $response_data = $response1->json();

                if ($response_data['response'] == 'Device is logged out') {
                    \Log::info("Notification Warning: Failed to send notification, Device is logged out");
                } else {
                    $tagihan_to_update = array_merge($tagihan_to_update, $group_tagihan_biaya->pluck('id_tagihan_biaya')->toArray());

                    \Log::info("Notification Success: Notification attendance sent at " . now());
                }

                sleep(rand(19, 29));
            }

            if (!empty($tagihan_to_update)) {
                TagihanBiaya::whereIn('id_tagihan_biaya', $tagihan_to_update)->update(['notification_sent' => 1]);
            }
        } catch (\Exception $e) {
            \Log::info("Notification Error: " . $e);
        }
    }
}
