<?php

namespace App\Console;

use App\Console\Commands\Notifications\PruneFcmTokens;
use App\Console\Commands\Promo\ProcessPromoCampaigns;
use App\Console\Commands\Promo\SchedulePromoCampaign;
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

        $schedule->command(SchedulePromoCampaign::class)->everyMinute()->withoutOverlapping()->runInBackground();
        $schedule->command(ProcessPromoCampaigns::class)->everyTwoMinutes()->withoutOverlapping()->runInBackground();

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
