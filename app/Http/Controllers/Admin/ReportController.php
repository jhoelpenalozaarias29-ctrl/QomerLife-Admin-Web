<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\RedemptionRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Export\CsvExportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly TransactionRepositoryInterface $transactions,
        private readonly RedemptionRepositoryInterface $redemptions,
        private readonly CsvExportService $csvExportService,
    ) {
    }

    public function index(Request $request)
    {
        $type = $request->query('type', 'transactions');
        if (!in_array($type, ['transactions', 'users', 'redemptions'])) {
            $type = 'transactions';
        }

        $search = trim($request->query('search', ''));
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $material = strtoupper(trim($request->query('material', '')));
        $status = strtoupper(trim($request->query('status', '')));
        $role = strtoupper(trim($request->query('role', '')));

        $items = [];
        $kpis = [];

        if ($type === 'transactions') {
            $all = $this->transactions->all();

            // Filtro por búsqueda
            if ($search !== '') {
                $searchLower = strtolower($search);
                $all = array_filter($all, function ($tx) use ($searchLower) {
                    return str_contains(strtolower($tx['token'] ?? ''), $searchLower)
                        || str_contains(strtolower($tx['id'] ?? ''), $searchLower)
                        || str_contains(strtolower($tx['userId'] ?? ''), $searchLower)
                        || str_contains(strtolower($tx['machineId'] ?? ''), $searchLower);
                });
            }

            // Filtro por material
            if ($material !== '') {
                $all = array_filter($all, function ($tx) use ($material) {
                    return strtoupper($tx['material'] ?? '') === $material;
                });
            }

            // Filtro por rango de fechas
            if ($dateFrom || $dateTo) {
                $fromTs = $dateFrom ? strtotime($dateFrom . ' 00:00:00') : 0;
                $toTs = $dateTo ? strtotime($dateTo . ' 23:59:59') : PHP_INT_MAX;

                $all = array_filter($all, function ($tx) use ($fromTs, $toTs) {
                    $ts = $this->extractTimestamp($tx['fechaCreacion'] ?? $tx['createdAt'] ?? null);
                    return $ts >= $fromTs && $ts <= $toTs;
                });
            }

            // Ordenar descendente
            usort($all, fn($a, $b) => $this->extractTimestamp($b['fechaCreacion'] ?? $b['createdAt'] ?? null)
                <=> $this->extractTimestamp($a['fechaCreacion'] ?? $a['createdAt'] ?? null));

            $items = array_values($all);

            // KPIs
            $totalPoints = 0;
            $totalPet = 0;
            $totalAlum = 0;
            foreach ($items as $tx) {
                $totalPoints += (int)($tx['ecoPoints'] ?? 0);
                $totalPet += (int)($tx['plasticBottlesCount'] ?? ($tx['bottles'] ?? 0));
                $totalAlum += (int)($tx['aluminumCansCount'] ?? ($tx['cans'] ?? 0));
            }

            $kpis = [
                'totalRecords' => count($items),
                'totalPoints'  => $totalPoints,
                'totalPet'     => $totalPet,
                'totalAlum'    => $totalAlum,
            ];

        } elseif ($type === 'users') {
            $allUsers = $this->users->all();

            // Filtro por búsqueda
            if ($search !== '') {
                $searchLower = strtolower($search);
                $allUsers = array_filter($allUsers, function ($u) use ($searchLower) {
                    return str_contains(strtolower($u->name ?? ''), $searchLower)
                        || str_contains(strtolower($u->email ?? ''), $searchLower)
                        || str_contains(strtolower($u->uid ?? ''), $searchLower);
                });
            }

            // Filtro por rol
            if ($role !== '') {
                $allUsers = array_filter($allUsers, function ($u) use ($role) {
                    return strtoupper($u->role ?? 'USER') === $role;
                });
            }

            // Filtro por estado
            if ($status !== '') {
                $allUsers = array_filter($allUsers, function ($u) use ($status) {
                    return strtoupper($u->status ?? 'ACTIVE') === $status;
                });
            }

            // Filtro por fecha de creación o último login si hay rango
            if ($dateFrom || $dateTo) {
                $fromTs = $dateFrom ? strtotime($dateFrom . ' 00:00:00') : 0;
                $toTs = $dateTo ? strtotime($dateTo . ' 23:59:59') : PHP_INT_MAX;

                $allUsers = array_filter($allUsers, function ($u) use ($fromTs, $toTs) {
                    $ts = 0;
                    if ($u->lastLogin instanceof \DateTimeInterface) {
                        $ts = $u->lastLogin->getTimestamp();
                    } elseif (isset($u->attributes['createdAt'])) {
                        $ts = $this->extractTimestamp($u->attributes['createdAt']);
                    }
                    return $ts === 0 || ($ts >= $fromTs && $ts <= $toTs);
                });
            }

            $items = array_values($allUsers);

            $totalUserPoints = 0;
            $adminCount = 0;
            $activeCount = 0;
            foreach ($items as $u) {
                $totalUserPoints += (int)($u->attributes['ecoPoints'] ?? 0);
                if (strtoupper($u->role ?? '') === 'ADMIN') $adminCount++;
                if (strtoupper($u->status ?? '') === 'ACTIVE') $activeCount++;
            }

            $kpis = [
                'totalRecords' => count($items),
                'totalPoints'  => $totalUserPoints,
                'adminCount'   => $adminCount,
                'activeCount'  => $activeCount,
            ];

        } elseif ($type === 'redemptions') {
            $all = $this->redemptions->all();

            // Filtro por búsqueda
            if ($search !== '') {
                $searchLower = strtolower($search);
                $all = array_filter($all, function ($r) use ($searchLower) {
                    return str_contains(strtolower($r['id'] ?? ''), $searchLower)
                        || str_contains(strtolower($r['userId'] ?? ''), $searchLower)
                        || str_contains(strtolower($r['rewardName'] ?? ($r['rewardId'] ?? '')), $searchLower);
                });
            }

            // Filtro por estado
            if ($status !== '') {
                $all = array_filter($all, function ($r) use ($status) {
                    return strtoupper($r['status'] ?? 'PENDING') === $status;
                });
            }

            // Filtro por rango de fechas
            if ($dateFrom || $dateTo) {
                $fromTs = $dateFrom ? strtotime($dateFrom . ' 00:00:00') : 0;
                $toTs = $dateTo ? strtotime($dateTo . ' 23:59:59') : PHP_INT_MAX;

                $all = array_filter($all, function ($r) use ($fromTs, $toTs) {
                    $ts = $this->extractTimestamp($r['createdAt'] ?? null);
                    return $ts >= $fromTs && $ts <= $toTs;
                });
            }

            // Ordenar descendente
            usort($all, fn($a, $b) => $this->extractTimestamp($b['createdAt'] ?? null)
                <=> $this->extractTimestamp($a['createdAt'] ?? null));

            $items = array_values($all);

            $redeemedPoints = 0;
            $pendingCount = 0;
            $approvedCount = 0;
            foreach ($items as $r) {
                $redeemedPoints += (int)($r['ecoPoints'] ?? ($r['pointsCost'] ?? 0));
                $st = strtoupper($r['status'] ?? 'PENDING');
                if ($st === 'PENDING') $pendingCount++;
                if ($st === 'APPROVED') $approvedCount++;
            }

            $kpis = [
                'totalRecords'   => count($items),
                'redeemedPoints' => $redeemedPoints,
                'pendingCount'   => $pendingCount,
                'approvedCount'  => $approvedCount,
            ];
        }

        return view('admin.reports.index', [
            'breadcrumbs' => ['Dashboard' => route('admin.dashboard'), 'Reportes' => null],
            'type'        => $type,
            'items'       => $items,
            'kpis'        => $kpis,
            'filters'     => [
                'search'    => $search,
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'material'  => $material,
                'status'    => $status,
                'role'      => $role,
            ],
        ]);
    }

    public function exportUsersCsv(Request $request)
    {
        $search = trim($request->query('search', ''));
        $role = strtoupper(trim($request->query('role', '')));
        $status = strtoupper(trim($request->query('status', '')));
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $allUsers = $this->users->all();

        if ($search !== '') {
            $searchLower = strtolower($search);
            $allUsers = array_filter($allUsers, function ($u) use ($searchLower) {
                return str_contains(strtolower($u->name ?? ''), $searchLower)
                    || str_contains(strtolower($u->email ?? ''), $searchLower)
                    || str_contains(strtolower($u->uid ?? ''), $searchLower);
            });
        }

        if ($role !== '') {
            $allUsers = array_filter($allUsers, fn($u) => strtoupper($u->role ?? 'USER') === $role);
        }

        if ($status !== '') {
            $allUsers = array_filter($allUsers, fn($u) => strtoupper($u->status ?? 'ACTIVE') === $status);
        }

        $headers = ['UID', 'Nombre', 'Email', 'Rol', 'Estado', 'EcoPoints', 'Último Acceso'];
        $rows = [];

        foreach ($allUsers as $user) {
            $lastLoginStr = 'N/A';
            if ($user->lastLogin instanceof \DateTimeInterface) {
                $lastLoginStr = $user->lastLogin->format('Y-m-d H:i:s');
            } elseif (isset($user->attributes['lastLogin'])) {
                $lastLoginStr = (string)$user->attributes['lastLogin'];
            }

            $rows[] = [
                $user->uid ?? '',
                $user->name ?? '',
                $user->email ?? '',
                $user->role ?? 'USER',
                $user->status ?? 'ACTIVE',
                $user->attributes['ecoPoints'] ?? 0,
                $lastLoginStr,
            ];
        }

        $filename = 'reporte_usuarios_' . date('Y-m-d_H-i') . '.csv';

        return $this->csvExportService->exportStream($filename, $headers, $rows);
    }

    public function exportTransactionsCsv(Request $request)
    {
        $search = trim($request->query('search', ''));
        $material = strtoupper(trim($request->query('material', '')));
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $allTransactions = $this->transactions->all();

        if ($search !== '') {
            $searchLower = strtolower($search);
            $allTransactions = array_filter($allTransactions, function ($tx) use ($searchLower) {
                return str_contains(strtolower($tx['token'] ?? ''), $searchLower)
                    || str_contains(strtolower($tx['id'] ?? ''), $searchLower)
                    || str_contains(strtolower($tx['userId'] ?? ''), $searchLower)
                    || str_contains(strtolower($tx['machineId'] ?? ''), $searchLower);
            });
        }

        if ($material !== '') {
            $allTransactions = array_filter($allTransactions, fn($tx) => strtoupper($tx['material'] ?? '') === $material);
        }

        if ($dateFrom || $dateTo) {
            $fromTs = $dateFrom ? strtotime($dateFrom . ' 00:00:00') : 0;
            $toTs = $dateTo ? strtotime($dateTo . ' 23:59:59') : PHP_INT_MAX;

            $allTransactions = array_filter($allTransactions, function ($tx) use ($fromTs, $toTs) {
                $ts = $this->extractTimestamp($tx['fechaCreacion'] ?? $tx['createdAt'] ?? null);
                return $ts >= $fromTs && $ts <= $toTs;
            });
        }

        usort($allTransactions, fn($a, $b) => $this->extractTimestamp($b['fechaCreacion'] ?? $b['createdAt'] ?? null)
            <=> $this->extractTimestamp($a['fechaCreacion'] ?? $a['createdAt'] ?? null));

        $headers = ['ID / Token', 'Usuario ID', 'Material', 'Botellas PET', 'Latas Aluminio', 'EcoPoints Otorgados', 'Máquina ID', 'Estado', 'Fecha'];
        $rows = [];

        foreach ($allTransactions as $tx) {
            $dateStr = 'N/A';
            $ts = $this->extractTimestamp($tx['fechaCreacion'] ?? $tx['createdAt'] ?? null);
            if ($ts > 0) {
                $dateStr = date('Y-m-d H:i:s', $ts);
            }

            $rows[] = [
                $tx['token'] ?? ($tx['id'] ?? ''),
                $tx['userId'] ?? 'Anónimo',
                $tx['material'] ?? 'PET',
                $tx['plasticBottlesCount'] ?? ($tx['bottles'] ?? 0),
                $tx['aluminumCansCount'] ?? ($tx['cans'] ?? 0),
                $tx['ecoPoints'] ?? 0,
                $tx['machineId'] ?? 'N/A',
                ($tx['usado'] ?? false) ? 'CANJEADO' : ($tx['estado'] ?? 'DISPONIBLE'),
                $dateStr,
            ];
        }

        $filename = 'reporte_transacciones_' . date('Y-m-d_H-i') . '.csv';

        return $this->csvExportService->exportStream($filename, $headers, $rows);
    }

    public function exportRedemptionsCsv(Request $request)
    {
        $search = trim($request->query('search', ''));
        $status = strtoupper(trim($request->query('status', '')));
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $allRedemptions = $this->redemptions->all();

        if ($search !== '') {
            $searchLower = strtolower($search);
            $allRedemptions = array_filter($allRedemptions, function ($r) use ($searchLower) {
                return str_contains(strtolower($r['id'] ?? ''), $searchLower)
                    || str_contains(strtolower($r['userId'] ?? ''), $searchLower)
                    || str_contains(strtolower($r['rewardName'] ?? ($r['rewardId'] ?? '')), $searchLower);
            });
        }

        if ($status !== '') {
            $allRedemptions = array_filter($allRedemptions, fn($r) => strtoupper($r['status'] ?? 'PENDING') === $status);
        }

        if ($dateFrom || $dateTo) {
            $fromTs = $dateFrom ? strtotime($dateFrom . ' 00:00:00') : 0;
            $toTs = $dateTo ? strtotime($dateTo . ' 23:59:59') : PHP_INT_MAX;

            $allRedemptions = array_filter($allRedemptions, function ($r) use ($fromTs, $toTs) {
                $ts = $this->extractTimestamp($r['createdAt'] ?? null);
                return $ts >= $fromTs && $ts <= $toTs;
            });
        }

        usort($allRedemptions, fn($a, $b) => $this->extractTimestamp($b['createdAt'] ?? null)
            <=> $this->extractTimestamp($a['createdAt'] ?? null));

        $headers = ['ID Canje', 'Usuario ID', 'Recompensa', 'EcoPoints Canjeados', 'Estado', 'Fecha'];
        $rows = [];

        foreach ($allRedemptions as $redemption) {
            $dateStr = 'N/A';
            $ts = $this->extractTimestamp($redemption['createdAt'] ?? null);
            if ($ts > 0) {
                $dateStr = date('Y-m-d H:i:s', $ts);
            }

            $rows[] = [
                $redemption['id'] ?? '',
                $redemption['userId'] ?? '',
                $redemption['rewardName'] ?? ($redemption['rewardId'] ?? 'Recompensa'),
                $redemption['ecoPoints'] ?? ($redemption['pointsCost'] ?? 0),
                $redemption['status'] ?? 'PENDING',
                $dateStr,
            ];
        }

        $filename = 'reporte_canjes_' . date('Y-m-d_H-i') . '.csv';

        return $this->csvExportService->exportStream($filename, $headers, $rows);
    }

    private function extractTimestamp(mixed $val): int
    {
        if (!$val) return 0;
        if (is_object($val) && method_exists($val, 'get')) {
            $dt = $val->get();
            return $dt instanceof \DateTimeInterface ? $dt->getTimestamp() : 0;
        }
        if ($val instanceof \DateTimeInterface) return $val->getTimestamp();
        return is_numeric($val) ? (int)$val : (strtotime((string)$val) ?: 0);
    }
}

