<?php
namespace App\Providers;

use Carbon\Carbon;
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
        Passport::tokensExpireIn(Carbon::now()->addMinutes(60));

        // Register Passport routes
        Passport::enablePasswordGrant();

    }

}
