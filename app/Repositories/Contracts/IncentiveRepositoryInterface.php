<?php

namespace App\Repositories\Contracts;

use App\Models\Incentive;

interface IncentiveRepositoryInterface
{
    /**
     * @return Incentive[]
     */
    public function all(): array;

    public function find(string $id): ?Incentive;

    public function create(array $data): string;

    public function update(string $id, array $data): void;

    public function delete(string $id): void;

    public function toggleAvailability(string $id): bool;

    public function seedDefaults(): int;
}
