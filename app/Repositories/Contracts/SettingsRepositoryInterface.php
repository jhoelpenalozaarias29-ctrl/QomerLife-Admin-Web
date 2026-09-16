<?php

namespace App\Repositories\Contracts;

interface SettingsRepositoryInterface
{
    public function all(): array;

    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): void;

    public function setMany(array $data): void;
}
