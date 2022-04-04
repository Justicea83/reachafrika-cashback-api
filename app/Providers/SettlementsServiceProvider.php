<?php

namespace App\Providers;

use App\Services\Settlements\ISettlementService;
use App\Services\Settlements\SettlementService;
use Illuminate\Support\ServiceProvider;

class SettlementsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(ISettlementService::class,SettlementService::class);
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
