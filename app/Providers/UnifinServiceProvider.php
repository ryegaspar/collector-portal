<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UnifinServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind("App\\Unifin\\Repositories\\Recalls\\Contracts\\RecallInterface",
            "App\\Unifin\\Repositories\\Recalls\\Recall");
    }
}
