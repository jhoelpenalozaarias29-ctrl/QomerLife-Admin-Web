<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepository
    ) {
    }

    public function index(Request $request)
    {
        $transactions = [];
        $search   = trim($request->query('search', ''));
        $material = strtoupper(trim($request->query('material', '')));
        $dateFrom = $request->query('date_from');
        $dateTo   = $request->query('date_to');

        try {
            $all = $this->transactionRepository->all();

            if ($search !== '') {
                $searchLower = strtolower($search);
                $all = array_filter($all, function ($tx) use ($searchLower) {
                    return str_contains(strtolower($tx['token']     ?? ''), $searchLower)
                        || str_contains(strtolower($tx['id']        ?? ''), $searchLower)
                        || str_contains(strtolower($tx['userId']    ?? ''), $searchLower)
                        || str_contains(strtolower($tx['machineId'] ?? ''), $searchLower);
                });
            }

            if ($material !== '') {
                $all = array_filter($all, function ($tx) use ($material) {
                    return strtoupper($tx['material'] ?? '') === $material;
                });
            }

            if ($dateFrom || $dateTo) {
                $fromTs = $dateFrom ? strtotime($dateFrom . ' 00:00:00') : 0;
                $toTs   = $dateTo ? strtotime($dateTo . ' 23:59:59') : PHP_INT_MAX;

                $all = array_filter($all, function ($tx) use ($fromTs, $toTs) {
                    $ts = $this->getTs($tx);
                    return $ts >= $fromTs && $ts <= $toTs;
                });
            }

            usort($all, fn($a, $b) => $this->getTs($b) <=> $this->getTs($a));

            $transactions = array_values($all);

        } catch (\Exception $e) {
            Log::error('Error fetching transactions: ' . $e->getMessage());
        }

        $totalPoints = 0;
        $totalBottles = 0;
        $totalCans = 0;
        foreach ($transactions as $t) {
            $totalPoints += (int)($t['ecoPoints'] ?? 0);
            $totalBottles += (int)($t['plasticBottlesCount'] ?? ($t['bottles'] ?? 0));
            $totalCans += (int)($t['aluminumCansCount'] ?? ($t['cans'] ?? 0));
        }

        return view('admin.transactions.index', [
            'breadcrumbs'  => ['Dashboard' => route('admin.dashboard'), 'Transacciones' => null],
            'transactions' => $transactions,
            'search'       => $search,
            'material'     => $material,
            'date_from'    => $dateFrom,
            'date_to'      => $dateTo,
            'kpis'         => [
                'total'   => count($transactions),
                'points'  => $totalPoints,
                'bottles' => $totalBottles,
                'cans'    => $totalCans,
            ]
        ]);
    }

    private function getTs(array $item): int
    {
        $val = $item['fechaCreacion'] ?? $item['createdAt'] ?? null;
        if (!$val) return 0;
        if (is_object($val) && method_exists($val, 'get')) return $val->get()->getTimestamp();
        if ($val instanceof \DateTimeInterface) return $val->getTimestamp();
        return is_numeric($val) ? (int)$val : (strtotime((string)$val) ?: 0);
    }

    public function show(string $id)
    {
        try {
            $transaction = $this->transactionRepository->find($id);
            if (!$transaction) {
                abort(404, 'Transaction not found');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching transaction: ' . $e->getMessage());
            abort(404, 'Transaction not found');
        }

        return view('admin.transactions.show', [
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard'),
                'Transactions' => route('admin.transactions.index'),
                'Transaction Details' => null
            ],
            'transaction' => $transaction
        ]);
    }
}
