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
        $search   = $request->query('search');
        $material = strtoupper($request->query('material', ''));

        try {
            // Traer todo y filtrar en PHP (Firestore REST no soporta múltiples filtros fácilmente)
            $all = $this->transactionRepository->all();

            // Filtro por búsqueda de texto
            if ($search) {
                $searchLower = strtolower($search);
                $all = array_filter($all, function ($tx) use ($searchLower) {
                    return str_contains(strtolower($tx['token']     ?? ''), $searchLower)
                        || str_contains(strtolower($tx['id']        ?? ''), $searchLower)
                        || str_contains(strtolower($tx['userId']    ?? ''), $searchLower)
                        || str_contains(strtolower($tx['machineId'] ?? ''), $searchLower);
                });
            }

            // Filtro por material
            if ($material) {
                $all = array_filter($all, function ($tx) use ($material) {
                    return strtoupper($tx['material'] ?? '') === $material;
                });
            }

            // Ordenar por fechaCreacion descendente (más reciente primero)
            usort($all, function ($a, $b) {
                $getTs = function ($item) {
                    $val = $item['fechaCreacion'] ?? $item['createdAt'] ?? null;
                    if (!$val) return 0;
                    if (is_object($val) && method_exists($val, 'get')) return $val->get()->getTimestamp();
                    if ($val instanceof \DateTimeInterface) return $val->getTimestamp();
                    return is_numeric($val) ? (int) $val : strtotime((string) $val);
                };
                return $getTs($b) <=> $getTs($a);
            });

            $transactions = array_values($all);

        } catch (\Exception $e) {
            Log::error('Error fetching transactions: ' . $e->getMessage());
        }

        return view('admin.transactions.index', [
            'breadcrumbs'  => ['Dashboard' => route('admin.dashboard'), 'Transacciones' => null],
            'transactions' => $transactions,
            'search'       => $search,
            'material'     => $material,
        ]);
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
