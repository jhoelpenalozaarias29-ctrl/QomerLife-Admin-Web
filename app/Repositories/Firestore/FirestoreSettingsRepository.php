<?php

namespace App\Repositories\Firestore;

use App\Repositories\Contracts\SettingsRepositoryInterface;
use App\Services\Firebase\FirestoreClient;

class FirestoreSettingsRepository implements SettingsRepositoryInterface
{
    private const DOCUMENT_ID = 'general';

    public function __construct(
        private readonly FirestoreClient $firestore,
        private readonly string $collection,
    ) {
    }

    public function all(): array
    {
        $snapshot = $this->firestore->find($this->collection, self::DOCUMENT_ID);
        
        if ($snapshot && $snapshot->exists()) {
            return $snapshot->data();
        }
        
        return [];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();
        
        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->firestore->document($this->collection, self::DOCUMENT_ID)->set(
            [$key => $value],
            ['merge' => true]
        );
    }

    public function setMany(array $data): void
    {
        $this->firestore->document($this->collection, self::DOCUMENT_ID)->set(
            $data,
            ['merge' => true]
        );
    }
}
