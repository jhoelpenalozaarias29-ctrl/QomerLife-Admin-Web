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

    public function index()
    {
        return view('admin.reports.index');
    }

    public function exportUsersCsv()
    {
        $allUsers = $this->users->all();

        $headers = ['UID', 'Nombre', 'Email', 'Rol', 'EcoPoints', 'Último Login', 'Fecha Creación'];
        $rows = [];

        foreach ($allUsers as $user) {
            $rows[] = [
                $user->uid ?? '',
                $user->displayName ?? '',
                $user->email ?? '',
                $user->role ?? 'USER',
                $user->ecoPoints ?? 0,
                $user->lastLoginAt ?? 'N/A',
                $user->createdAt ?? 'N/A',
            ];
        }

        $filename = 'reporte_usuarios_' . date('Y-m-d_H-i') . '.csv';

        return $this->csvExportService->exportStream($filename, $headers, $rows);
    }

    public function exportTransactionsCsv()
    {
        $allTransactions = $this->transactions->all();

        $headers = ['ID', 'Usuario ID', 'Tipo', 'Botellas', 'Latas', 'EcoPoints Otorgados', 'Máquina ID', 'Fecha'];
        $rows = [];

        foreach ($allTransactions as $tx) {
            $rows[] = [
                $tx['id'] ?? '',
                $tx['userId'] ?? '',
                $tx['type'] ?? 'RECYCLING',
                $tx['bottles'] ?? 0,
                $tx['cans'] ?? 0,
                $tx['ecoPoints'] ?? 0,
                $tx['machineId'] ?? 'N/A',
                $tx['createdAt'] ?? (isset($tx['timestamp']) ? date('Y-m-d H:i:s', is_numeric($tx['timestamp']) ? $tx['timestamp'] : strtotime((string)$tx['timestamp'])) : 'N/A'),
            ];
        }

        $filename = 'reporte_transacciones_' . date('Y-m-d_H-i') . '.csv';

        return $this->csvExportService->exportStream($filename, $headers, $rows);
    }

    public function exportRedemptionsCsv()
    {
        $allRedemptions = $this->redemptions->all();

        $headers = ['ID', 'Usuario ID', 'Recompensa', 'Puntos Canjeados', 'Estado', 'Fecha'];
        $rows = [];

        foreach ($allRedemptions as $redemption) {
            $rows[] = [
                $redemption['id'] ?? '',
                $redemption['userId'] ?? '',
                $redemption['rewardName'] ?? ($redemption['rewardId'] ?? 'Recompensa'),
                $redemption['pointsCost'] ?? 0,
                $redemption['status'] ?? 'PENDING',
                $redemption['createdAt'] ?? 'N/A',
            ];
        }

        $filename = 'reporte_canjes_' . date('Y-m-d_H-i') . '.csv';

        return $this->csvExportService->exportStream($filename, $headers, $rows);
    }
}
