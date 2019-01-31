<?php

namespace App\Providers;

use App\Models\Lynx\Adjustment;
use App\Models\Lynx\DeskTransferRequest;
use App\Models\Lynx\LetterRequest;
use App\Policies\AdjustmentPolicy;
use App\Policies\DeskTransferRequestPolicy;
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
        Adjustment::class          => AdjustmentPolicy::class,
        LetterRequest::class       => LetterRequestPolicy::class,
        DeskTransferRequest::class => DeskTransferRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
