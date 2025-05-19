<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Complaint::class => ComplaintPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Register Passport routes
        Passport::enablePasswordGrant();

    }

}
