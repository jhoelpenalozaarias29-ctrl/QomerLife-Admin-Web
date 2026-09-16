<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory as FirebaseFactory;
use Kreait\Firebase\Contract\Auth as FirebaseAuthContract;
use Kreait\Firebase\Contract\Firestore as FirestoreContract;
use Kreait\Firebase\Contract\Storage as FirebaseStorageContract;
use App\Services\Firebase\FirestoreClient;
use App\Services\Firebase\FirebaseAuthService;
use App\Services\Firebase\FirebaseStorageService;

/**
 * Registra los clientes de Firebase (Auth, Firestore, Storage) como
 * singletons de la aplicación, a partir de una única cuenta de servicio.
 *
 * Ningún otro punto del código debe hacer `new Factory()`: siempre se
 * inyecta App\Services\Firebase\* o los contratos de kreait/firebase-php.
 */
class FirebaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FirebaseFactory::class, function () {
            $factory = new FirebaseFactory();

            $credentials = config('firebase.credentials');
            if ($credentials) {
                if (file_exists($credentials)) {
                    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentials);
                    $_ENV['GOOGLE_APPLICATION_CREDENTIALS'] = $credentials;
                    $_SERVER['GOOGLE_APPLICATION_CREDENTIALS'] = $credentials;
                }
                $factory = $factory->withServiceAccount($credentials);
            }

            if ($projectId = config('firebase.project_id')) {
                $factory = $factory->withProjectId($projectId);
            }

            if ($databaseUrl = config('firebase.database_url')) {
                $factory = $factory->withDatabaseUri($databaseUrl);
            }

            if ($bucket = config('firebase.storage.default_bucket')) {
                $factory = $factory->withDefaultStorageBucket($bucket);
            }

            $factory = $factory->withFirestoreClientConfig(['transport' => 'rest']);

            return $factory;
        });

        $this->app->singleton(FirestoreContract::class, function ($app) {
            return $app->make(FirebaseFactory::class)->createFirestore();
        });

        $this->app->singleton(FirebaseAuthContract::class, function ($app) {
            return $app->make(FirebaseFactory::class)->createAuth();
        });

        $this->app->singleton(FirebaseStorageContract::class, function ($app) {
            return $app->make(FirebaseFactory::class)->createStorage();
        });

        // Wrappers de dominio (los que el resto de la app realmente usa)
        $this->app->singleton(FirestoreClient::class, function ($app) {
            return new FirestoreClient($app->make(FirestoreContract::class));
        });

        $this->app->singleton(FirebaseAuthService::class, function ($app) {
            return new FirebaseAuthService($app->make(FirebaseAuthContract::class));
        });

        $this->app->singleton(FirebaseStorageService::class, function ($app) {
            return new FirebaseStorageService($app->make(FirebaseStorageContract::class));
        });
    }
}
