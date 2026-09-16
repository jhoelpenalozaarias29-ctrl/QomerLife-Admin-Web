<?php

namespace App\Repositories\Contracts;

interface RedemptionRepositoryInterface
{
    public function all(): array;

    public function find(string $id): ?array;

    public function count(): int;

    public function getRecent(int $limit = 10): array;

    public function updateStatus(string $id, string $status): void;
}
