<?php

namespace App\Providers;

use App\Services\Promo\Campaign\IPromoCampaignService;
use App\Services\Promo\Campaign\PromoCampaignService;
use App\Services\Promo\IPromoService;
use App\Services\Promo\PromoService;
use Illuminate\Support\ServiceProvider;

class PromoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(IPromoService::class,PromoService::class);
        $this->app->scoped(IPromoCampaignService::class,PromoCampaignService::class);
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
