<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\FirestoreUserProvider;
use App\Services\Firebase\FirestoreClient;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\\Models\\Model' => 'App\\Policies\\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Auth::provider('firestore', function ($app, array $config) {
            return new FirestoreUserProvider(
                $app->make(FirestoreClient::class),
                config('firebase.collections.users', 'users')
            );
        });
    }
}
