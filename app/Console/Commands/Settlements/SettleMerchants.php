<?php

namespace App\Console\Commands\Settlements;

use App\Services\Settlements\ISettlementService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SettleMerchants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchants:settle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command ensures merchants receives their monies on their accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle(ISettlementService $settlementService): int
    {
        $settlementService->settleMerchants();
        return CommandAlias::SUCCESS;
    }
}
