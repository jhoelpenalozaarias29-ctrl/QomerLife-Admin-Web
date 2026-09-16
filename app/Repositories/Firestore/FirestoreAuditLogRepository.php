<?php

namespace App\Repositories\Firestore;

use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Services\Firebase\FirestoreClient;
use Illuminate\Support\Facades\Auth;

class FirestoreAuditLogRepository implements AuditLogRepositoryInterface
{
    public function __construct(
        private readonly FirestoreClient $firestore,
        private readonly string $collection,
    ) {
    }

    public function all(): array
    {
        $documents = $this->firestore->collection($this->collection)->documents();
        
        $logs = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                $logs[] = $data;
            }
        }
        
        return $logs;
    }

    public function log(string $action, string $description, ?string $userId = null, ?array $metadata = null): void
    {
        $user = Auth::user();
        
        $data = [
            'action' => $action,
            'description' => $description,
            'userId' => $userId,
            'metadata' => $metadata,
            'ip' => request()->ip(),
            'timestamp' => new \DateTimeImmutable(),
            'performedBy' => $user ? [
                'uid' => $user->getAuthIdentifier(),
                'name' => method_exists($user, 'getName') ? $user->getName() : ($user->name ?? 'Unknown'),
            ] : null,
        ];

        $newId = uniqid('log_', true);
        $this->firestore->document($this->collection, $newId)->set($data);
    }

    public function getRecent(int $limit = 50): array
    {
        $logs = $this->all();
        
        usort($logs, function ($a, $b) {
            $timeA = 0;
            if (isset($a['timestamp'])) {
                $timeA = is_object($a['timestamp']) && method_exists($a['timestamp'], 'get') 
                    ? $a['timestamp']->get()->getTimestamp() 
                    : (is_numeric($a['timestamp']) ? $a['timestamp'] : strtotime((string) $a['timestamp']));
            }
            
            $timeB = 0;
            if (isset($b['timestamp'])) {
                $timeB = is_object($b['timestamp']) && method_exists($b['timestamp'], 'get') 
                    ? $b['timestamp']->get()->getTimestamp() 
                    : (is_numeric($b['timestamp']) ? $b['timestamp'] : strtotime((string) $b['timestamp']));
            }
            
            return $timeB <=> $timeA;
        });
        
        return array_slice($logs, 0, $limit);
    }
}
