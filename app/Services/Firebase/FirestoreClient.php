<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Contract\Firestore;
use Google\Cloud\Firestore\CollectionReference;
use Google\Cloud\Firestore\DocumentReference;
use Google\Cloud\Firestore\DocumentSnapshot;

/**
 * Wrapper de bajo nivel sobre el SDK de Firestore.
 *
 * Ningún Repository debe hablar con `Kreait\Firebase\Contract\Firestore`
 * directamente: todos pasan por aquí. Esto permite, por ejemplo, añadir
 * logging, métricas de lecturas/escrituras (importante por costos) o
 * cambiar de SDK en un solo lugar.
 */
class FirestoreClient
{
    public function __construct(private readonly Firestore $firestore)
    {
    }

    public function collection(string $name): CollectionReference
    {
        return $this->firestore->database()->collection($name);
    }

    public function document(string $collection, string $id): DocumentReference
    {
        return $this->collection($collection)->document($id);
    }

    public function find(string $collection, string $id): ?DocumentSnapshot
    {
        $snapshot = $this->document($collection, $id)->snapshot();

        return $snapshot->exists() ? $snapshot : null;
    }

    /**
     * Ejecuta una escritura dentro de una transacción Firestore.
     * Usar para operaciones que deben ser atómicas (ej. registrar una
     * transacción y descontar/otorgar EcoPoints al usuario a la vez).
     */
    public function runTransaction(callable $callback): mixed
    {
        return $this->firestore->database()->runTransaction($callback);
    }
}
