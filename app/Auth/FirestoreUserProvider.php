<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Services\Firebase\FirestoreClient;
use App\Models\FirestoreUser;
use Illuminate\Support\Facades\Log;

class FirestoreUserProvider implements UserProvider
{
    public function __construct(
        private readonly FirestoreClient $firestore,
        private readonly string $collection,
    ) {
    }

    public function retrieveById($identifier): ?Authenticatable
    {
        if (empty($identifier)) {
            return null;
        }

        try {
            $snapshot = $this->firestore->find($this->collection, (string) $identifier);
            return $snapshot ? FirestoreUser::fromFirestore($snapshot->data(), $snapshot->id()) : null;
        } catch (\Throwable $e) {
            Log::warning("FirestoreUserProvider: No se pudo recuperar usuario [{$identifier}] desde Firestore por cuota o red: " . $e->getMessage());
            return null;
        }
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        // No se utiliza "remember me" por ahora en el panel.
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): bool
    {
        return false;
    }
}
