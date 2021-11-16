<?php

namespace App\Providers;

use App\Services\Collection\CollectionService;
use App\Services\Collection\ICollectionService;
use Illuminate\Support\ServiceProvider;

class CollectionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(ICollectionService::class,CollectionService::class);

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
