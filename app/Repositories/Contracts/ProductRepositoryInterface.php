<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function all(): array;
    public function find(string $id): ?array;
    public function create(array $data): void;
    public function update(string $id, array $data): void;
    public function delete(string $id): void;
}
