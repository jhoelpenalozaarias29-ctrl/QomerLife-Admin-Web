<?php

namespace App\Models;

class Incentive
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $ecoPointsRequired,
        public string $category = 'General',
        public int $availableQuantity = 100,
        public bool $isAvailable = true,
        public string $imageUrl = '',
        public ?\DateTimeInterface $createdAt = null,
        public ?\DateTimeInterface $updatedAt = null,
        public array $attributes = []
    ) {
    }

    public static function fromFirestore(array $data, string $id): self
    {
        return new self(
            id: $id,
            title: $data['title'] ?? ($data['titulo'] ?? ''),
            description: $data['description'] ?? ($data['descripcion'] ?? ''),
            ecoPointsRequired: (int)($data['ecoPointsRequired'] ?? ($data['ecoPoints'] ?? 0)),
            category: $data['category'] ?? ($data['categoria'] ?? 'General'),
            availableQuantity: (int)($data['availableQuantity'] ?? ($data['stock'] ?? 0)),
            isAvailable: (bool)($data['isAvailable'] ?? ($data['activo'] ?? true)),
            imageUrl: $data['imageUrl'] ?? ($data['imagen'] ?? ''),
            createdAt: isset($data['createdAt']) ? self::parseDateTime($data['createdAt']) : null,
            updatedAt: isset($data['updatedAt']) ? self::parseDateTime($data['updatedAt']) : null,
            attributes: $data,
        );
    }

    public function toFirestore(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'ecoPointsRequired' => $this->ecoPointsRequired,
            'category' => $this->category,
            'availableQuantity' => $this->availableQuantity,
            'isAvailable' => $this->isAvailable,
            'imageUrl' => $this->imageUrl,
            'updatedAt' => new \DateTimeImmutable(),
        ];
    }

    private static function parseDateTime(mixed $val): ?\DateTimeInterface
    {
        if (!$val) return null;
        if (is_object($val) && method_exists($val, 'get')) return $val->get();
        if ($val instanceof \DateTimeInterface) return $val;
        if (is_numeric($val)) return (new \DateTimeImmutable())->setTimestamp((int)$val);
        try {
            return new \DateTimeImmutable((string)$val);
        } catch (\Throwable) {
            return null;
        }
    }
}
