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
        Commands\PaymentDeadline::class,
        Commands\FullyBooked::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('payment:deadline')->dailyAt('00:01');
        $schedule->command('fully:booked')->dailyAt('00:01');
        $schedule->command('upcoming:event')->dailyAt('00:01');
        $schedule->command('paymentstatus:update')->everyMinute();
        $schedule->command('booking:cancelled')->everyMinute();
        $schedule->command('hallbooking:cancelled')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
