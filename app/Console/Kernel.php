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
        Commands\SendAttendanceNotification::class,
        Commands\SendPaymentNotification::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notification:attendance')
            ->hourly()
            ->between('8:00', '15:00')
            ->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY, Schedule::SATURDAY])
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();

        $schedule->command('notification:payment')
            ->twiceDaily(11, 15)
            ->between('8:00', '15:00')
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
