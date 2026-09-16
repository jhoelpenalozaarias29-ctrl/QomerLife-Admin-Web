<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Auth\SignIn\FailedToSignIn;

/**
 * Encapsula todo lo relacionado a autenticación de Firebase que el panel
 * necesita: login por email/password (vía REST de Identity Toolkit) y
 * verificación local de ID Tokens (sin llamada de red por request,
 * gracias al cache de claves públicas que maneja el SDK internamente).
 */
class FirebaseAuthService
{
    public function __construct(private readonly Auth $auth)
    {
    }

    /**
     * Autentica contra Firebase Identity Toolkit y devuelve el resultado
     * (incluye idToken, refreshToken, localId/uid).
     *
     * @throws FailedToSignIn
     */
    public function signInWithPassword(string $email, string $password): array
    {
        $result = $this->auth->signInWithEmailAndPassword($email, $password);

        return [
            'uid' => $result->data()['localId'] ?? $result->firebaseUserId(),
            'id_token' => $result->idToken(),
            'refresh_token' => $result->refreshToken(),
        ];
    }

    /**
     * Verifica localmente un ID Token (firma + expiración + issuer).
     * Devuelve el UID si es válido, o null si no lo es.
     */
    public function verifyIdToken(string $idToken): ?string
    {
        try {
            $verified = $this->auth->verifyIdToken($idToken);

            return $verified->claims()->get('sub');
        } catch (FailedToVerifyToken) {
            return null;
        }
    }

    public function getUserRecord(string $uid): ?\Kreait\Firebase\Auth\UserRecord
    {
        try {
            return $this->auth->getUser($uid);
        } catch (\Throwable) {
            return null;
        }
    }
}
