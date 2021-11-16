<?php

namespace App\Providers;

use App\Services\Merchant\IMerchantService;
use App\Services\Merchant\MerchantService;
use App\Services\Merchant\Pos\IPosService;
use App\Services\Merchant\Pos\PosService;
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
