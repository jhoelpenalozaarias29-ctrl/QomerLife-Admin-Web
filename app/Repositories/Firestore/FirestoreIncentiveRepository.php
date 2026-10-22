<?php

namespace App\Repositories\Firestore;

use App\Models\Incentive;
use App\Repositories\Contracts\IncentiveRepositoryInterface;
use App\Services\Firebase\FirestoreClient;
use Illuminate\Support\Str;

class FirestoreIncentiveRepository implements IncentiveRepositoryInterface
{
    public function __construct(
        private readonly FirestoreClient $firestore,
        private readonly string $collection = 'incentives'
    ) {
    }

    /**
     * @return Incentive[]
     */
    public function all(): array
    {
        $documents = $this->firestore->collection($this->collection)->documents();
        $list = [];

        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $list[] = Incentive::fromFirestore($doc->data(), $doc->id());
            }
        }

        // Ordenar por categoría y luego título
        usort($list, function ($a, $b) {
            $catComp = strcmp($a->category, $b->category);
            return $catComp !== 0 ? $catComp : strcmp($a->title, $b->title);
        });

        return $list;
    }

    public function find(string $id): ?Incentive
    {
        $doc = $this->firestore->collection($this->collection)->document($id)->snapshot();
        if ($doc->exists()) {
            return Incentive::fromFirestore($doc->data(), $doc->id());
        }

        return null;
    }

    public function create(array $data): string
    {
        // Generar ID amigable o usar slug
        $id = !empty($data['id']) ? Str::slug($data['id']) : ('inc_' . Str::slug($data['title']) . '_' . substr(uniqid(), -4));

        $payload = [
            'id' => $id,
            'title' => trim($data['title'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'ecoPointsRequired' => (int)($data['ecoPointsRequired'] ?? 0),
            'category' => trim($data['category'] ?? 'General'),
            'availableQuantity' => (int)($data['availableQuantity'] ?? 100),
            'isAvailable' => (bool)($data['isAvailable'] ?? true),
            'imageUrl' => trim($data['imageUrl'] ?? ''),
            'createdAt' => new \DateTimeImmutable(),
            'updatedAt' => new \DateTimeImmutable(),
        ];

        $this->firestore->collection($this->collection)->document($id)->set($payload);

        return $id;
    }

    public function update(string $id, array $data): void
    {
        $payload = [
            'title' => trim($data['title'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'ecoPointsRequired' => (int)($data['ecoPointsRequired'] ?? 0),
            'category' => trim($data['category'] ?? 'General'),
            'availableQuantity' => (int)($data['availableQuantity'] ?? 0),
            'isAvailable' => (bool)($data['isAvailable'] ?? true),
            'imageUrl' => trim($data['imageUrl'] ?? ''),
            'updatedAt' => new \DateTimeImmutable(),
        ];

        $this->firestore->collection($this->collection)->document($id)->set($payload, ['merge' => true]);
    }

    public function delete(string $id): void
    {
        $this->firestore->collection($this->collection)->document($id)->delete();
    }

    public function toggleAvailability(string $id): bool
    {
        $incentive = $this->find($id);
        if (!$incentive) {
            throw new \InvalidArgumentException("Incentivo no encontrado: {$id}");
        }

        $newStatus = !$incentive->isAvailable;
        $this->firestore->collection($this->collection)->document($id)->set([
            'isAvailable' => $newStatus,
            'updatedAt' => new \DateTimeImmutable(),
        ], ['merge' => true]);

        return $newStatus;
    }

    public function seedDefaults(): int
    {
        $defaults = [
            [
                'id' => 'inc_cafe',
                'title' => 'Café Orgánico Universitario',
                'description' => 'Disfruta de un café americano o expreso gratis en la cafetería central.',
                'ecoPointsRequired' => 100,
                'category' => 'Cafetería',
                'availableQuantity' => 50,
                'isAvailable' => true,
                'imageUrl' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'id' => 'inc_impresiones',
                'title' => 'Bono de Impresiones (20 Páginas)',
                'description' => 'Bono para imprimir tus trabajos académicos en el centro de fotocopiado.',
                'ecoPointsRequired' => 150,
                'category' => 'Impresiones',
                'availableQuantity' => 100,
                'isAvailable' => true,
                'imageUrl' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'id' => 'inc_termo',
                'title' => 'Termo Reutilizable Q\'omer Life',
                'description' => 'Termo ecológico de acero inoxidable con aislamiento térmico y logo del proyecto.',
                'ecoPointsRequired' => 500,
                'category' => 'Merchandising',
                'availableQuantity' => 25,
                'isAvailable' => true,
                'imageUrl' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'id' => 'inc_almuerzo',
                'title' => 'Descuento 50% Menú Saludable',
                'description' => 'Válido para el menú vegetariano o ensalada completa en el comedor universitario.',
                'ecoPointsRequired' => 250,
                'category' => 'Descuentos',
                'availableQuantity' => 40,
                'isAvailable' => true,
                'imageUrl' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80',
            ],
        ];

        $created = 0;
        foreach ($defaults as $item) {
            $existing = $this->find($item['id']);
            if (!$existing) {
                $this->create($item);
                $created++;
            }
        }

        return $created;
    }
}
