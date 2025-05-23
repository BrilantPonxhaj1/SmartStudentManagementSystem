<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use Tests\CreatesApplication;

abstract class FeatureTestCase extends BaseTestCase
{
    use  RefreshDatabase;

    /**
     * Method we use on tests for different user roles
     */
    protected function actingAsRole(string $role)
    {
        $user = User::create([
            'first_name'     => 'Test',
            'last_name'      => 'User',
            'email'          => 'testuser@example.com',
            'password'       => bcrypt('password'),
            'type'           => $role,
        ]);        Passport::actingAs($user);
        return $user;
    }
}
