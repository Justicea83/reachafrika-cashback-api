<?php

namespace App\Console\Commands\Promo;

use App\Services\Promo\Campaign\IPromoCampaignService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ProcessPromoCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo_campaign:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command processes promo campaigns, making them as visible or drying them out';

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
        $promoCampaignService->processCampaigns();
        return CommandAlias::SUCCESS;
    }
}
