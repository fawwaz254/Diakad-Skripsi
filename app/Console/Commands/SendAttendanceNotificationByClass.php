<?php

namespace App\Console\Commands;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Setting;
use Illuminate\Console\Command;
use App\Models\PresensiPengguna;
use App\Models\ManajemenHariLibur;
use Illuminate\Support\Facades\Http;
use App\Models\WaNotifKehadiranSiswa;

class SendAttendanceNotificationByClass extends Command
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
    protected $description = 'Send Attendance Notification via WhatsApp by class';

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
                \Log::info("Notification Warning: Failed to send notification, API URL not found");
                return;
            }

            $now = now()->toDateString();
            $hari_libur = ManajemenHariLibur::where('date', $now)->exists();

            if ($hari_libur) {
                \Log::info("Notification Warning: Failed to send notification, today is a day off");
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
            $base_template = Setting::where('key_setting', 'template_notif_kehadiran_siswa')->value('value');

            if ($mode === 'PRESENT_ONLY' || $mode === 'ALL') {
                $list_presensi_pengguna_group = PresensiPengguna::with('pengguna.siswa.kelas')
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
                        $siswa_kelas[] = "[*" . $presensi_pengguna->pengguna->siswa->nis_siswa . "*] " . $presensi_pengguna->pengguna->nm_pengguna . ' || Masuk: ' . $presensi_pengguna->check_in;
                    };

                    $template = $base_template;
                    $message = str_replace(
                        ['{{CLASS}}', '{{DATE}}', '\n'],
                        [$key, now()->translatedFormat('l, d F Y'), "\n"],
                        $template
                    );
                    $content_message = join("\n -------------------------------------------------------------------------------- \n", $siswa_kelas);
                    $message .= $content_message;
                    $message .= "\n\n\nJika Anda memiliki pertanyaan terkait kesiswaan atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Kesiswaan " . $nama_sekolah . "*";

                    $data = [
                        'message' => $message,
                        'group_id' => $kelas[$key],
                    ];

                    $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                        ->post($url, $data);

                    $response_data = $response->json();

                    if ($response_data['response'] == 'Device is logged out') {
                        \Log::info("Notification Warning: Failed to send notification, device is logged out");
                    } else {
                        $notif_kehadiran = new WaNotifKehadiranSiswa();
                        $notif_kehadiran->id_notif = strtotime($now) . uniqid();
                        $notif_kehadiran->id_siswa = $presensi_pengguna->pengguna->siswa->id_siswa;
                        $notif_kehadiran->save();

                        \Log::info("Notification Success: Notification attendance sent at " . now());
                    }

                    sleep(rand(19, 29));
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
                    ->whereNotNull('id_kelas')
                    ->get()
                    ->groupBy('kelas.nm_kelas');

                foreach ($list_siswa_group as $key => $list_siswa) {
                    if (!isset($kelas[$key])) {
                        continue;
                    }

                    $siswa_kelas = [];
                    foreach ($list_siswa as $siswa) {
                        $siswa_kelas[] = "[*" . $siswa->nis_siswa . "*] " . $siswa->pengguna->nm_pengguna;
                    };

                    $template = $base_template;
                    $message = str_replace(
                        ['{{CLASS}}', '{{DATE}}', '\n'],
                        [$key, now()->translatedFormat('l, d F Y'), "\n"],
                        $template
                    );
                    $content_message = join("\n -------------------------------------------------------------------------------- \n", $siswa_kelas);
                    $message .= $content_message;
                    $message .= "\n\n\nJika Anda memiliki pertanyaan terkait kesiswaan atau informasi lainnya, jangan ragu untuk menghubungi kami.\n\nTerima kasih atas perhatian dan kerjasama Anda.\n\n\nSalam,\n*Kesiswaan " . $nama_sekolah . "*";

                    $data = [
                        'message' => $message,
                        'group_id' => $kelas[$key],
                    ];

                    $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                        ->post($url, $data);

                    $response_data = $response->json();

                    if ($response_data['response'] == 'Device is logged out') {
                        \Log::info("Notification Warning: Failed to send notification, device is logged out");
                    } else {
                        $notif_kehadiran = new WaNotifKehadiranSiswa();
                        $notif_kehadiran->id_notif = strtotime($now) . uniqid();
                        $notif_kehadiran->id_siswa = $siswa->id_siswa;
                        $notif_kehadiran->save();

                        \Log::info("Notification Success: Notification attendance sent at " . now());
                    }

                    sleep(rand(19, 29));
                }
            }
        } catch (\Exception $e) {
            \Log::info("Notification Error: " . $e);
        }
    }
}
