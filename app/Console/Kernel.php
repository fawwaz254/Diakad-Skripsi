<?php

namespace App\Console;

use App\Models\Sekolah;
use App\Models\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        Commands\SendAttendanceNotificationByClass::class,
        Commands\SendPaymentNotificationByClass::class,
        Commands\FetchWhatsappGroups::class,
        Commands\ClearSesiPengguna::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $attendance_time_setting = Setting::where('key_setting', 'jadwal_jam_notif_kehadiran_siswa')->value('value');
        $payment_time_setting = Setting::where('key_setting', 'jadwal_jam_notif_pembayaran_spp')->value('value');
        $schedule_setting = Setting::where('key_setting', 'jadwal_hari_notifikasi')->value('value');
        $array_schedule = explode('|', $schedule_setting);
        $schedule_to_number = array_map('convertDayToNumber', $array_schedule);


        $except_school = Sekolah::first();

        $schedule->command('notification:attendance-class')
            ->dailyAt($attendance_time_setting)
            ->days($schedule_to_number)
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();


        $schedule->command('notification:payment-class')
            ->dailyAt($payment_time_setting)
            ->days($schedule_to_number)
            ->when(function () use ($except_school) {
                if ($except_school->id_sekolah == null) {
                    return 0;
                } else {
                    return $except_school->id_sekolah !== 'B9hY715358553135b8b4ad12f588';
                }
            })
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();

        $schedule->command('whatsapp:groups')
            ->hourly(30)
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();

        $schedule->command('sesi-pengguna:clear')
            ->dailyAt('02:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
