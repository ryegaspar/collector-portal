<?php

namespace App\Providers;

use App\Models\Lynx\Collector;
use App\Observers\Lynx\CollectorObserver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('hash', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, auth()->user()->password);
        });

        Collector::observe(CollectorObserver::class);

//        DB::listen(function($query) {
//            Log::info($query->sql, $query->bindings, $query->time);
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
