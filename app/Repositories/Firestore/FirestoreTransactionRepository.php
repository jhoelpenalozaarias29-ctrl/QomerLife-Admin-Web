<?php

namespace App\Repositories\Firestore;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Services\Firebase\FirestoreClient;

class FirestoreTransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private readonly FirestoreClient $firestore,
        private readonly string $collection,
    ) {
    }

    public function all(): array
    {
        $documents = $this->firestore->collection($this->collection)->documents();
        
        $transactions = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                $transactions[] = $data;
            }
        }
        
        return $transactions;
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

    public function search(string $query): array
    {
        $allTransactions = $this->all();
        $query = strtolower($query);
        
        $results = [];
        foreach ($allTransactions as $transaction) {
            $searchable = strtolower(json_encode($transaction));
            if (str_contains($searchable, $query)) {
                $results[] = $transaction;
            }
        }
        
        return $results;
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
        $transactions = $this->all();
        
        usort($transactions, function ($a, $b) {
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
        
        return array_slice($transactions, 0, $limit);
    }

    public function sumField(string $field): float
    {
        $documents = $this->firestore->collection($this->collection)->documents();
        $sum = 0.0;
        
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $data = $doc->data();
                if (isset($data[$field]) && is_numeric($data[$field])) {
                    $sum += (float) $data[$field];
                }
            }
        }
        
        return $sum;
    }
}
