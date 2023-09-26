<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Setting;
use Illuminate\Console\Command;
use App\Models\PresensiPengguna;
use App\Models\ManajemenHariLibur;
use Illuminate\Support\Facades\Http;
use App\Models\WaNotifKehadiranSiswa;

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

            $nama_sekolah = Sekolah::value('nm_sekolah');
            $list_notif_terkirim = WaNotifKehadiranSiswa::whereDate('created_at', $now)->pluck('id_siswa')->toArray();
            $mode = Setting::where('key_setting', 'mode_notif_kehadiran_siswa')->value('value');
            $base_template = Setting::where('key_setting', 'template_notif_kehadiran_siswa')->value('value');

            if ($mode === 'PRESENT_ONLY' || $mode === 'ALL') {
                $list_presensi_pengguna = PresensiPengguna::with('pengguna.siswa.wali_murid')
                    ->whereHas('pengguna.siswa.wali_murid', function ($q) {
                        $q->whereNotNull('nomor_hp_wali_murid');
                    })
                    ->where('status_join_table', 3)
                    ->where('date', $now)
                    ->take(250)
                    ->get();

                foreach ($list_presensi_pengguna as $presensi_pengguna) {
                    if (in_array($presensi_pengguna->pengguna->siswa->id_siswa, $list_notif_terkirim)) {
                        continue;
                    }

                    $formatted_date = now()->translatedFormat('l, d F Y');
                    $formatted_check_in = date('H:i', strtotime($presensi_pengguna->check_in));
                    $template = $base_template;
                    $message = str_replace(
                        ['{{STUDENT_NAME}}', '{{SCHOOL_NAME}}', '{{DATE}}', '{{CHECK_IN}}'],
                        [$presensi_pengguna->pengguna->nm_pengguna, $nama_sekolah, $formatted_date, $formatted_check_in],
                        $template
                    );

                    $data = [
                        'message' => $message,
                        'phone' => $presensi_pengguna->pengguna->siswa->wali_murid,
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

                $list_siswa = Siswa::with([
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
                    ->take(50)
                    ->get();

                foreach ($list_siswa as $siswa) {
                    if (in_array($siswa->id_siswa, $list_notif_terkirim)) {
                        continue;
                    }

                    $formatted_date = now()->translatedFormat('l, d F Y');
                    $template = $base_template;
                    $message = str_replace(
                        ['{{STUDENT_NAME}}', '{{SCHOOL_NAME}}', '{{DATE}}'],
                        [$siswa->pengguna->nm_pengguna, $nama_sekolah, $formatted_date],
                        $template
                    );

                    $data = [
                        'message' => $message,
                        'phone' => $siswa->wali_murid->nomor_hp_wali_murid,
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
