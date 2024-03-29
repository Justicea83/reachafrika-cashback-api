<?php

namespace App\Console;

use App\Console\Commands\Notifications\PruneFcmTokens;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('passport:purge')->hourly();
        $schedule->command(PruneFcmTokens::class)->daily()->withoutOverlapping()->runInBackground();

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
