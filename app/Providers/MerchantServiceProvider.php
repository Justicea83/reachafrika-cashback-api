<?php

namespace App\Providers;

use App\Services\Merchant\IMerchantService;
use App\Services\Merchant\MerchantService;
use App\Services\Merchant\Pos\IPosService;
use App\Services\Merchant\Pos\PosService;
use App\Services\Merchant\Reports\IReportsService;
use App\Services\Merchant\Reports\ReportsService;
use App\Services\Merchant\Transactions\ITransactionsService;
use App\Services\Merchant\Transactions\TransactionsService;
use Illuminate\Support\ServiceProvider;

class MerchantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(IMerchantService::class,MerchantService::class);
        $this->app->scoped(IPosService::class,PosService::class);
        $this->app->scoped(ITransactionsService::class,TransactionsService::class);
        $this->app->scoped(IReportsService::class,ReportsService::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
