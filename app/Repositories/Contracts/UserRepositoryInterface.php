<?php

namespace App\Repositories\Contracts;

use App\Models\FirestoreUser;

interface UserRepositoryInterface
{
    public function findByUid(string $uid): ?FirestoreUser;

    public function updateLastLogin(string $uid): void;

    /**
     * @return FirestoreUser[]
     */
    public function all(): array;

    /**
     * @return FirestoreUser[]
     */
    public function search(string $query): array;

    public function update(string $uid, array $data): void;

    public function delete(string $uid): void;

    public function count(): int;
}
