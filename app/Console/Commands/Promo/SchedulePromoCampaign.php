<?php

namespace App\Console\Commands\Promo;

use App\Services\Promo\Campaign\IPromoCampaignService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SchedulePromoCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo_campaign:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command schedules promo campaigns';

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
     * @param IPromoCampaignService $promoCampaignService
     * @return int
     */
    public function handle(IPromoCampaignService $promoCampaignService): int
    {
        $promoCampaignService->schedule();
        return CommandAlias::SUCCESS;
    }
}