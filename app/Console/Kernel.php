<?php

namespace App\Console;

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
        Commands\SendAttendanceNotififcationByClass::class,
        Commands\SendPaymentNotificationByClass::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $attendance_time_setting = Setting::where('key_setting', 'jadwal_jam_notif_kehadiran_siswa')->value('value');
        $payment_time_setting = Setting::where('key_setting', 'jadwal_jam_notif_pembayaran_spp')->value('value');

        $schedule->command('notification:attendance-class')
            ->dailyAt($attendance_time_setting)
            ->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY, Schedule::SATURDAY])
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();

        $schedule->command('notification:payment-class')
            ->dailyAt($payment_time_setting)
            ->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY, Schedule::SATURDAY])
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
