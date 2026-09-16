<?php

namespace App\Repositories\Firestore;

use App\Models\FirestoreUser;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Firebase\FirestoreClient;

class FirestoreUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly FirestoreClient $firestore,
        private readonly string $collection,
    ) {
    }

    public function findByUid(string $uid): ?FirestoreUser
    {
        $snapshot = $this->firestore->find($this->collection, $uid);

        return $snapshot ? FirestoreUser::fromFirestore($snapshot->data(), $snapshot->id()) : null;
    }

    public function updateLastLogin(string $uid): void
    {
        $document = $this->firestore->document($this->collection, $uid);
        $document->update([
            ['path' => 'lastLogin', 'value' => new \DateTimeImmutable()],
        ]);
    }

    /**
     * @return FirestoreUser[]
     */
    public function all(): array
    {
        $documents = $this->firestore->collection($this->collection)->documents();
        
        $users = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $users[] = FirestoreUser::fromFirestore($document->data(), $document->id());
            }
        }
        
        return $users;
    }

    /**
     * @return FirestoreUser[]
     */
    public function search(string $query): array
    {
        $allUsers = $this->all();
        $query = strtolower($query);
        
        $results = [];
        foreach ($allUsers as $user) {
            $nameMatch = str_contains(strtolower($user->displayName ?? ''), $query);
            $emailMatch = str_contains(strtolower($user->email ?? ''), $query);
            
            if ($nameMatch || $emailMatch) {
                $results[] = $user;
            }
        }
        
        return $results;
    }

    public function update(string $uid, array $data): void
    {
        $this->firestore->document($this->collection, $uid)->set($data, ['merge' => true]);
    }

    public function delete(string $uid): void
    {
        $this->firestore->document($this->collection, $uid)->delete();
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
}
