<?php

namespace App\Console\Commands;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sekolah;
use Illuminate\Console\Command;
use App\Models\PresensiPengguna;
use App\Models\ManajemenHariLibur;
use Illuminate\Support\Facades\Http;
use App\Models\WaNotifKehadiranSiswa;

class SendAttendanceNotififcationByClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:attendance-class';

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
        try {
            $url = env('WHATSAPP_API_SEND');
            if (empty($url)) {
                return;
            }

            $now = now()->toDateString();
            $hari_libur = ManajemenHariLibur::where('date', $now)->exists();

            if ($hari_libur) {
                return;
            }

            $list_kelas = Kelas::has('whatsapp_group')->with('whatsapp_group')->get();
            $kelas = [];
            foreach ($list_kelas as $item) {
                $nm_kelas = $item->nm_kelas;
                $id_group = $item->whatsapp_group->id_group;
                $kelas[$nm_kelas] = $id_group;
            }

            $nama_sekolah = Sekolah::value('nm_sekolah');
            $mode = Setting::where('key_setting', 'mode_notif_kehadiran_siswa')->value('value');

            if ($mode === 'PRESENT_ONLY' || $mode === 'ALL') {
                $list_presensi_pengguna_group = PresensiPengguna::with('pengguna.siswa.wali_murid', 'pengguna.siswa.kelas')
                    ->whereHas('pengguna.siswa.wali_murid', function ($q) {
                        $q->whereNotNull('nomor_hp_wali_murid');
                    })
                    ->where('status_join_table', 3)
                    ->where('date', $now)
                    ->get()
                    ->groupBy('pengguna.siswa.kelas.nm_kelas');

                foreach ($list_presensi_pengguna_group as $key => $list_presensi_pengguna) {
                    if (!isset($kelas[$key])) {
                        continue;
                    }

                    $siswa_kelas = [];
                    foreach ($list_presensi_pengguna as $presensi_pengguna) {
                        $siswa_kelas[] = $presensi_pengguna->pengguna->nm_pengguna . ' || Masuk: ' . $presensi_pengguna->check_in;
                    };

                    $message = join("\n -------------------------------------------------------------------------------- \n", $siswa_kelas);
                    $message = "*Notifikasi Kehadiran Siswa Harian*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami dengan senang hati memberitahukan kehadiran putra/putri Anda di sekolah hari ini, " . now()->translatedFormat('l, d F Y') . "\n\n\n" . $message;
                    $message .= "\n\n\nJika Anda memiliki pertanyaan terkait kesiswaan atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Kesiswaan " . $nama_sekolah . "*";

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
                        $notif_kehadiran = new WaNotifKehadiranSiswa();
                        $notif_kehadiran->id_notif = strtotime($now) . uniqid();
                        $notif_kehadiran->id_siswa = $presensi_pengguna->pengguna->siswa->id_siswa;
                        $notif_kehadiran->save();

                        \Log::info("Notification Success: Notification attendance sent at " . now());
                    }

                    sleep(rand(5, 20));
                }
            }

            if ($mode === 'ABSENT_ONLY' || $mode === 'ALL') {
                $id_pengguna_hadir = PresensiPengguna::where('status_join_table', 3)
                    ->where('date', $now)
                    ->pluck('id_pengguna')
                    ->toArray();

                $list_siswa_group = Siswa::with([
                    'kelas',
                    'pengguna.presensi_pengguna',
                    'wali_murid',
                    'pengguna.shiftPengguna' => function ($q) use ($now) {
                        $q->where('date', $now)->with('shift_master');
                    }
                ])
                    ->whereHas('pengguna', function ($q) use ($id_pengguna_hadir) {
                        $q->where('status_join_table', 3)->whereNotIn('pengguna.id_pengguna', $id_pengguna_hadir);
                    })
                    ->whereHas('pengguna.status_pengguna', function ($q) {
                        $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
                    })
                    ->whereHas('wali_murid', function ($q) {
                        $q->whereNotNull('nomor_hp_wali_murid');
                    })
                    ->whereNotNull('id_kelas')
                    ->get()
                    ->groupBy('kelas.nm_kelas');

                foreach ($list_siswa_group as $key => $list_siswa) {
                    if (!isset($kelas[$key])) {
                        continue;
                    }

                    $siswa_kelas = [];
                    foreach ($list_siswa as $siswa) {
                        $siswa_kelas[] = $siswa->pengguna->nm_pengguna;
                    };

                    $message = join("\n -------------------------------------------------------------------------------- \n", $siswa_kelas);
                    $message = "*Notifikasi Ketidakhadiran Siswa Harian*\n\n\nAssalamualaikum Wr.Wb. Bapak/Ibu Wali Murid,\n\nKami dengan berat hati memberitahukan ketidakhadiran putra/putri Anda di sekolah hari ini, " . now()->translatedFormat('l, d F Y') . "\n\n\n" . $message;
                    $message .= "\n\n\nJika Anda memiliki pertanyaan terkait kesiswaan atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Kesiswaan " . $nama_sekolah . "*";

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
                        $notif_kehadiran = new WaNotifKehadiranSiswa();
                        $notif_kehadiran->id_notif = strtotime($now) . uniqid();
                        $notif_kehadiran->id_siswa = $siswa->id_siswa;
                        $notif_kehadiran->save();

                        \Log::info("Notification Success: Notification attendance sent at " . now());
                    }

                    sleep(rand(5, 20));
                }
            }
        } catch (\Exception $e) {
            \Log::info("Notification Error: " . $e->getMessage());
        }
    }
}
