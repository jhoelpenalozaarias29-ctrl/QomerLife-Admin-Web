<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\IncentiveRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncentiveController extends Controller
{
    public function __construct(
        private readonly IncentiveRepositoryInterface $incentiveRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository
    ) {
    }

    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $category = trim($request->query('category', ''));
        $availability = trim($request->query('availability', ''));

        $incentives = [];
        try {
            $all = $this->incentiveRepository->all();

            if ($search !== '') {
                $searchLower = strtolower($search);
                $all = array_filter($all, function ($inc) use ($searchLower) {
                    return str_contains(strtolower($inc->title), $searchLower)
                        || str_contains(strtolower($inc->description), $searchLower)
                        || str_contains(strtolower($inc->category), $searchLower)
                        || str_contains(strtolower($inc->id), $searchLower);
                });
            }

            if ($category !== '') {
                $all = array_filter($all, fn($inc) => strcasecmp($inc->category, $category) === 0);
            }

            if ($availability === 'active') {
                $all = array_filter($all, fn($inc) => $inc->isAvailable === true);
            } elseif ($availability === 'inactive') {
                $all = array_filter($all, fn($inc) => $inc->isAvailable === false);
            }

            $incentives = array_values($all);
        } catch (\Exception $e) {
            Log::error('Error fetching incentives: ' . $e->getMessage());
        }

        // Obtener categorías únicas disponibles para el filtro
        $allIncentives = $this->incentiveRepository->all();
        $categories = array_unique(array_map(fn($i) => $i->category ?: 'General', $allIncentives));
        sort($categories);

        // Métricas rápidas
        $totalStock = 0;
        $totalPointsSum = 0;
        $activeCount = 0;
        foreach ($allIncentives as $inc) {
            $totalStock += $inc->availableQuantity;
            $totalPointsSum += $inc->ecoPointsRequired;
            if ($inc->isAvailable) $activeCount++;
        }
        $avgPoints = count($allIncentives) > 0 ? (int)round($totalPointsSum / count($allIncentives)) : 0;

        return view('admin.incentives.index', [
            'breadcrumbs' => ['Dashboard' => route('admin.dashboard'), 'Incentivos' => null],
            'incentives' => $incentives,
            'categories' => $categories,
            'search' => $search,
            'category' => $category,
            'availability' => $availability,
            'kpis' => [
                'total' => count($allIncentives),
                'active' => $activeCount,
                'avgPoints' => $avgPoints,
                'totalStock' => $totalStock,
            ]
        ]);
    }

    public function create()
    {
        return view('admin.incentives.create', [
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard'),
                'Incentivos' => route('admin.incentives.index'),
                'Nuevo Incentivo' => null,
            ],
            'categories' => ['Cafetería', 'Impresiones', 'Merchandising', 'Descuentos', 'Académico', 'General']
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|string|max:50',
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'ecoPointsRequired' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'availableQuantity' => 'required|integer|min:0',
            'imageUrl' => 'nullable|string|max:1000',
            'isAvailable' => 'nullable|boolean',
        ]);

        $validated['isAvailable'] = $request->boolean('isAvailable', true);

        try {
            $id = $this->incentiveRepository->create($validated);

            $this->auditLogRepository->log(
                action: 'CREATE_INCENTIVE',
                description: "Incentivo creado: {$validated['title']} ({$validated['ecoPointsRequired']} pts)",
                userId: auth()->id() ?? 'admin',
                metadata: ['id' => $id, 'data' => $validated]
            );

            return redirect()->route('admin.incentives.index')
                ->with('success', "Incentivo '{$validated['title']}' publicado exitosamente en el catálogo.");
        } catch (\Exception $e) {
            Log::error('Error creating incentive: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al crear incentivo: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $incentive = $this->incentiveRepository->find($id);
        if (!$incentive) {
            abort(404, 'Incentivo no encontrado.');
        }

        return view('admin.incentives.edit', [
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard'),
                'Incentivos' => route('admin.incentives.index'),
                'Editar Incentivo' => null,
            ],
            'incentive' => $incentive,
            'categories' => ['Cafetería', 'Impresiones', 'Merchandising', 'Descuentos', 'Académico', 'General']
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'ecoPointsRequired' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'availableQuantity' => 'required|integer|min:0',
            'imageUrl' => 'nullable|string|max:1000',
            'isAvailable' => 'nullable|boolean',
        ]);

        $validated['isAvailable'] = $request->boolean('isAvailable');

        try {
            $this->incentiveRepository->update($id, $validated);

            $this->auditLogRepository->log(
                action: 'UPDATE_INCENTIVE',
                description: "Incentivo actualizado: {$validated['title']}",
                userId: auth()->id() ?? 'admin',
                metadata: ['id' => $id, 'data' => $validated]
            );

            return redirect()->route('admin.incentives.index')
                ->with('success', "Incentivo '{$validated['title']}' actualizado correctamente.");
        } catch (\Exception $e) {
            Log::error('Error updating incentive: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al actualizar incentivo: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $incentive = $this->incentiveRepository->find($id);
            $title = $incentive ? $incentive->title : $id;

            $this->incentiveRepository->delete($id);

            $this->auditLogRepository->log(
                action: 'DELETE_INCENTIVE',
                description: "Incentivo eliminado: {$title} (ID: {$id})",
                userId: auth()->id() ?? 'admin',
                metadata: ['id' => $id]
            );

            return redirect()->route('admin.incentives.index')
                ->with('success', "Incentivo '{$title}' eliminado del catálogo.");
        } catch (\Exception $e) {
            Log::error('Error deleting incentive: ' . $e->getMessage());
            return redirect()->route('admin.incentives.index')
                ->with('error', 'Error al eliminar incentivo: ' . $e->getMessage());
        }
    }

    public function toggleAvailability(string $id)
    {
        try {
            $newStatus = $this->incentiveRepository->toggleAvailability($id);
            $statusText = $newStatus ? 'disponible para canje' : 'pausado / no disponible';

            return redirect()->back()->with('success', "El incentivo ahora está {$statusText}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo cambiar el estado: ' . $e->getMessage());
        }
    }

    public function seedDefaults()
    {
        try {
            $created = $this->incentiveRepository->seedDefaults();

            return redirect()->route('admin.incentives.index')
                ->with('success', "Se cargaron {$created} incentivos oficiales al catálogo de la app móvil.");
        } catch (\Exception $e) {
            return redirect()->route('admin.incentives.index')
                ->with('error', 'Error al cargar incentivos iniciales: ' . $e->getMessage());
        }
    }
}
