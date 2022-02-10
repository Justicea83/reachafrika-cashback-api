<?php

namespace App\Providers;

use App\Services\Notifications\Fcm\FcmNotificationService;
use App\Services\Notifications\Fcm\IFcmNotificationService;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(IFcmNotificationService::class, FcmNotificationService::class);
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
