<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class FirestoreUser implements AuthenticatableContract
{
    use Authenticatable;

    public ?string $remember_token = null;

    public function __construct(
        public string $uid,
        public string $name = '',
        public string $email = '',
        public ?string $photoUrl = null,
        public string $role = 'USER',
        public string $status = 'ACTIVE',
        public ?\DateTimeInterface $lastLogin = null,
        public array $attributes = []
    ) {
    }

    public static function fromFirestore(array $data, string $uid): self
    {
        $lastLogin = null;
        if (isset($data['lastLogin'])) {
            $val = $data['lastLogin'];
            if ($val instanceof \DateTimeInterface) {
                $lastLogin = $val;
            } elseif (is_object($val) && method_exists($val, 'get')) {
                $lastLogin = $val->get();
            } elseif (is_numeric($val)) {
                $lastLogin = (new \DateTimeImmutable())->setTimestamp((int) $val);
            } elseif (is_string($val)) {
                try {
                    $lastLogin = new \DateTimeImmutable($val);
                } catch (\Throwable) {
                    $lastLogin = null;
                }
            }
        }

        return new self(
            uid: $uid,
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            photoUrl: $data['photoUrl'] ?? null,
            role: $data['role'] ?? 'USER',
            status: $data['status'] ?? 'ACTIVE',
            lastLogin: $lastLogin,
            attributes: $data,
        );
    }

    public function isActive(): bool
    {
        return strtoupper($this->status) === 'ACTIVE';
    }

    public function isAdmin(): bool
    {
        return strtoupper($this->role) === 'ADMIN';
    }

    public function getAuthIdentifierName(): string
    {
        return 'uid';
    }

    public function getAuthIdentifier(): string
    {
        return $this->uid;
    }

    public function getAuthPassword(): ?string
    {
        return null;
    }
}
