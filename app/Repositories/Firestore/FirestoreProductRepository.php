<?php

namespace App\Repositories\Firestore;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Firebase\FirestoreClient;

class FirestoreProductRepository implements ProductRepositoryInterface
{
    private string $collectionName = 'productos';

    public function __construct(
        private readonly FirestoreClient $firestore
    ) {
    }

    public function all(): array
    {
        $documents = $this->firestore->collection($this->collectionName)->documents();
        $products = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                $data['id'] = $doc->id();
                $products[] = $data;
            }
        }
        return $products;
    }

    public function find(string $id): ?array
    {
        $doc = $this->firestore->collection($this->collectionName)->document($id)->snapshot();
        if ($doc->exists()) {
            $data = $doc->data();
            $data['id'] = $doc->id();
            return $data;
        }
        return null;
    }

    public function create(array $data): void
    {
        // The user says "productos que son los codigos de barras", so ID is barcode
        $id = $data['codigoBarras'] ?? null;
        if (!$id) {
            throw new \InvalidArgumentException("codigoBarras is required");
        }
        $this->firestore->collection($this->collectionName)->document($id)->set($data);
    }

    public function update(string $id, array $data): void
    {
        // We use merge to not overwrite the entire document if not needed, but set works well.
        $this->firestore->collection($this->collectionName)->document($id)->set($data, ['merge' => true]);
    }

    public function delete(string $id): void
    {
        $this->firestore->collection($this->collectionName)->document($id)->delete();
    }
}
