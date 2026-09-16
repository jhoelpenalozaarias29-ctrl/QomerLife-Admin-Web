<?php

namespace App\Repositories\Contracts;

interface TransactionRepositoryInterface
{
    public function all(): array;

    public function find(string $id): ?array;

    public function search(string $query): array;

    public function count(): int;

    public function getRecent(int $limit = 10): array;

    public function sumField(string $field): float;
}
