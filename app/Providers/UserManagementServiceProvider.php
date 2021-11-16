<?php

namespace App\Providers;

use App\Services\UserManagement\IUserManagementService;
use App\Services\UserManagement\UserManagementService;
use Illuminate\Support\ServiceProvider;

class UserManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(IUserManagementService::class,UserManagementService::class);
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
