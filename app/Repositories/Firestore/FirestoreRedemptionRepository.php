<?php

namespace App\Repositories\Firestore;

use App\Repositories\Contracts\RedemptionRepositoryInterface;
use App\Services\Firebase\FirestoreClient;

class FirestoreRedemptionRepository implements RedemptionRepositoryInterface
{
    public function __construct(
        private readonly FirestoreClient $firestore,
        private readonly string $collection,
    ) {
    }

    public function all(): array
    {
        $documents = $this->firestore->collection($this->collection)->documents();
        
        $redemptions = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                $redemptions[] = $data;
            }
        }
        
        return $redemptions;
    }

    public function find(string $id): ?array
    {
        $snapshot = $this->firestore->find($this->collection, $id);
        
        if ($snapshot && $snapshot->exists()) {
            $data = $snapshot->data();
            $data['id'] = $snapshot->id();
            return $data;
        }
        
        return null;
    }

    public function count(): int
    {
        $documents = $this->firestore->collection($this->collection)->documents();
        $count = 0;
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $count++;
            }
        }
        return $count;
    }

    public function getRecent(int $limit = 10): array
    {
        $redemptions = $this->all();
        
        usort($redemptions, function ($a, $b) {
            $timeA = 0;
            if (isset($a['createdAt'])) {
                $timeA = is_object($a['createdAt']) && method_exists($a['createdAt'], 'get') 
                    ? $a['createdAt']->get()->getTimestamp() 
                    : (is_numeric($a['createdAt']) ? $a['createdAt'] : strtotime((string) $a['createdAt']));
            }
            
            $timeB = 0;
            if (isset($b['createdAt'])) {
                $timeB = is_object($b['createdAt']) && method_exists($b['createdAt'], 'get') 
                    ? $b['createdAt']->get()->getTimestamp() 
                    : (is_numeric($b['createdAt']) ? $b['createdAt'] : strtotime((string) $b['createdAt']));
            }
            
            return $timeB <=> $timeA;
        });
        
        return array_slice($redemptions, 0, $limit);
    }

    public function updateStatus(string $id, string $status): void
    {
        $this->firestore->document($this->collection, $id)->set(
            ['status' => $status, 'updatedAt' => new \DateTimeImmutable()],
            ['merge' => true]
        );
    }
}
