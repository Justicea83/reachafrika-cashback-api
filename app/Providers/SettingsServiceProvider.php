<?php

namespace App\Providers;

use App\Services\Settings\BusinessInfo\BusinessInfoService;
use App\Services\Settings\BusinessInfo\IBusinessInfoService;
use App\Services\Settings\Cashback\CashbackService;
use App\Services\Settings\Cashback\ICashbackService;
use App\Services\Settings\Finance\PaymentMode\IPaymentModeService;
use App\Services\Settings\Finance\PaymentMode\PaymentModeService;
use App\Services\Settings\SettlementBank\ISettlementBankService;
use App\Services\Settings\SettlementBank\SettlementBankService;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(IBusinessInfoService::class,BusinessInfoService::class);
        $this->app->scoped(ISettlementBankService::class,SettlementBankService::class);
        $this->app->scoped(IPaymentModeService::class,PaymentModeService::class);
        $this->app->scoped(ICashbackService::class,CashbackService::class);
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
