<?php

namespace App\Providers;

use App\Models\Lynx\Adjustment;
use App\Models\Lynx\LetterRequest;
use App\Policies\AdjustmentPolicy;
use App\Policies\LetterRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'          => 'App\Policies\ModelPolicy',
        Adjustment::class    => AdjustmentPolicy::class,
        LetterRequest::class => LetterRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
//        $this->registerAdminPolicies();
    }

    /**
     * Register the admin's policies.
     *
     * @return void
     */
//    public function registerAdminPolicies()
//    {
//        Gate::define('access-superadmin', function ($user) {
//            return $user->access_level == 1;
//        });
//
//        Gate::define('access-admin', function ($user) {
//            return $user->access_level == 2;
//        });
//    }
}
