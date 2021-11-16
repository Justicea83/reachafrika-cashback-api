<?php

namespace App\Providers;

use App\Services\Category\CategoryService;
use App\Services\Category\ICategoryService;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->scoped(ICategoryService::class,CategoryService::class);
    }

    public function boot()
    {
        //
    }
}
