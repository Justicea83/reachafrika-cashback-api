<?php

namespace App\Providers;

use App\Services\Finance\Payment\Flutterwave\FlutterwaveService;
use App\Services\Finance\Payment\Flutterwave\IFlutterwaveService;
use App\Services\Finance\Payment\IPaymentService;
use App\Services\Finance\Payment\PaymentService;
use App\Services\Finance\Payment\Paystack\IPaystackService;
use App\Services\Finance\Payment\Paystack\PaystackService;
use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(IPaymentService::class, PaymentService::class);
        $this->app->scoped(IFlutterwaveService::class, FlutterwaveService::class);
        $this->app->scoped(IPaystackService::class, PaystackService::class);
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
