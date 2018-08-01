<?php

namespace App\Providers;

use App\Adjustment;
use App\Policies\AdjustmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'       => 'App\Policies\ModelPolicy',
        Adjustment::class => AdjustmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerAdminPolicies();

    }

    /**
     * Register the admin's policies.
     *
     * @return void
     */
    public function registerAdminPolicies()
    {
        Gate::define('access-superadmin', function ($user) {
            return $user->access_level <= 1;
        });

        Gate::define('access-admin', function ($user) {
            return $user->access_level <= 2;
        });
    }
}
