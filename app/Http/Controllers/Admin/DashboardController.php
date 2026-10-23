<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\RedemptionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly RedemptionRepositoryInterface $redemptionRepository
    ) {
    }

    public function index(Request $request)
    {
        // Si el usuario hace clic deliberado en "Recargar Datos", se limpia la caché
        if ($request->boolean('refresh')) {
            Cache::forget('admin.dashboard.data');
        }

        // Proteger Firestore con caché en memoria de 2 minutos para evitar lecturas repetitivas
        $data = Cache::remember('admin.dashboard.data', 120, function () {
            $totalUsers         = 0;
            $totalTransactions  = 0;
            $totalEcoPoints     = 0;
            $totalBottles       = 0;
            $totalCans          = 0;
            $totalRedemptions   = 0;
            $recentTransactions = [];
            $recentRedemptions  = [];

            try {
                $totalUsers = $this->userRepository->count();
            } catch (\Throwable $e) {
                Log::warning('Dashboard: Error al consultar usuarios en Firestore: ' . $e->getMessage());
            }

            try {
                $allTransactions   = $this->transactionRepository->all();
                $totalTransactions = count($allTransactions);

                foreach ($allTransactions as $tx) {
                    $totalEcoPoints += (int) ($tx['ecoPoints'] ?? 0);
                    $totalBottles   += (int) ($tx['plasticBottlesCount'] ?? $tx['bottleCount'] ?? 0);
                    $totalCans      += (int) ($tx['aluminumCansCount']   ?? $tx['canCount']    ?? 0);
                }

                usort($allTransactions, function ($a, $b) {
                    $getTs = function ($item) {
                        $val = $item['fechaCreacion'] ?? $item['createdAt'] ?? null;
                        if (!$val) return 0;
                        if (is_object($val) && method_exists($val, 'get')) return $val->get()->getTimestamp();
                        if ($val instanceof \DateTimeInterface) return $val->getTimestamp();
                        return is_numeric($val) ? (int) $val : strtotime((string) $val);
                    };
                    return $getTs($b) <=> $getTs($a);
                });
                $recentTransactions = array_slice($allTransactions, 0, 5);

            } catch (\Throwable $e) {
                Log::warning('Dashboard: Error al consultar transacciones en Firestore: ' . $e->getMessage());
            }

            try {
                $allRedemptions   = $this->redemptionRepository->all();
                $totalRedemptions = count($allRedemptions);

                usort($allRedemptions, function ($a, $b) {
                    $getTs = fn($item) => isset($item['timestamp'])
                        ? (is_object($item['timestamp']) && method_exists($item['timestamp'], 'get')
                            ? $item['timestamp']->get()->getTimestamp()
                            : (is_numeric($item['timestamp']) ? $item['timestamp'] : strtotime((string) $item['timestamp'])))
                        : 0;
                    return $getTs($b) <=> $getTs($a);
                });
                $recentRedemptions = array_slice($allRedemptions, 0, 5);

            } catch (\Throwable $e) {
                Log::warning('Dashboard: Error al consultar canjes en Firestore: ' . $e->getMessage());
            }

            return [
                'totalUsers'         => $totalUsers,
                'totalTransactions'  => $totalTransactions,
                'totalEcoPoints'     => $totalEcoPoints,
                'totalBottles'       => $totalBottles,
                'totalCans'          => $totalCans,
                'totalRedemptions'   => $totalRedemptions,
                'recentTransactions' => $recentTransactions,
                'recentRedemptions'  => $recentRedemptions,
                'lastSyncTime'       => date('H:i:s'),
            ];
        });

        return view('admin.dashboard.index', $data);
    }
}
