<?php

namespace App\Console\Commands\Notifications;

use App\Services\Notifications\Fcm\IFcmNotificationService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PruneFcmTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm_notifications:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes old notification tokens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle(IFcmNotificationService $fcmNotificationService): int
    {
        $fcmNotificationService->pruneNotificationTokens();
        return CommandAlias::SUCCESS;
    }
}
