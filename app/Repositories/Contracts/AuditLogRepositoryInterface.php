<?php

namespace App\Repositories\Contracts;

interface AuditLogRepositoryInterface
{
    public function all(): array;

    public function log(string $action, string $description, ?string $userId = null, ?array $metadata = null): void;

    public function getRecent(int $limit = 50): array;
}
