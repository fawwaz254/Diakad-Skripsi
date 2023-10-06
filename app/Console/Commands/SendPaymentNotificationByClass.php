<?php

namespace App\Console\Commands;

use App\Models\Kelas;
use App\Models\Sekolah;
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
        $url = env('WHATSAPP_API_SEND');
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

        // dd($list_tagihan_biaya); 1

        if ($list_tagihan_biaya->isEmpty() || empty($url)) {
            return 0;
        }

        try {
            $nama_sekolah = Sekolah::first()->nm_sekolah;

            $tagihan_to_update = [];

            foreach ($list_tagihan_biaya as $key => $tagihan_biaya) {
                if (!isset($kelas[$key])) {
                    continue;
                }

                $group_tagihan_biaya = $tagihan_biaya->groupBy('id_siswa');

                $siswa_kelas = [];

                // dd($group_tagihan_biaya); 2

                foreach ($group_tagihan_biaya as $tagihan) {

                    // dd($tagihan); 3

                    $nama_pengguna = '';
                    $bulan_pembayaran = '';

                    foreach ($tagihan as $t) {

                        // dd($t) 4

                        $nama_pengguna = $t->nm_pengguna;
                        $bulan_pembayaran .= $t->bulan_pembayaran . ', ';
                    }

                    $siswa_kelas[] = $nama_pengguna . " ( " . $bulan_pembayaran . ")";
                }

                $message = join("\n----------------------------------------------------------------------------------\n", $siswa_kelas);
                $message = "*Notifikasi Pembayaran SPP*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami ingin menginformasikan pembayaran SPP untuk putra/putri Anda hari ini, " . now()->translatedFormat('l, d F Y') . "\n\n\n" . $message;
                $message .= "\n\n\nJika Anda memiliki pertanyaan terkait pembayaran atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Keuangan " . $nama_sekolah . "*";

                $data = [
                    'message' => $message,
                    'group_id' => $kelas[$key],
                ];

                $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                $response_data = $response->json();

                if ($response_data['response'] == 'Device Bot Logged Out') {
                    \Log::info("Notification Warning: Failed to send notification, Device bot logged out");
                } else {
                    $tagihan_to_update = array_merge($tagihan_to_update, $group_tagihan_biaya->pluck('id_tagihan_biaya')->toArray());

                    \Log::info("Notification Success: Notification attendance sent at " . now());
                }

                sleep(rand(10, 20));
            }

            if (!empty($tagihan_to_update)) {
                TagihanBiaya::whereIn('id_tagihan_biaya', $tagihan_to_update)->update(['notification_sent' => 1]);
            }
        } catch (\Exception $e) {
            \Log::info("Notification Error: " . $e->getMessage());
        }
    }
}
