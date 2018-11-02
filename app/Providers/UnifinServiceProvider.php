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
        $this->app->bind("Unifin\\Repositories\\Recalls\\JcaRecallInterface",
            "Unifin\\Repositories\\Recalls\\JcaRecallEntity");
    }
}
