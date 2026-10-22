<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\RedemptionRepositoryInterface;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Support\Facades\Log;

class RedemptionController extends Controller
{
    public function __construct(
        private readonly RedemptionRepositoryInterface $redemptionRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository
    ) {
    }

    public function index(Request $request)
    {
        $redemptions = [];
        $search = trim($request->query('search', ''));
        $status = strtoupper(trim($request->query('status', '')));
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        try {
            $all = $this->redemptionRepository->all();

            if ($search !== '') {
                $searchLower = strtolower($search);
                $all = array_filter($all, function ($r) use ($searchLower) {
                    return str_contains(strtolower($r['id'] ?? ''), $searchLower)
                        || str_contains(strtolower($r['userId'] ?? ''), $searchLower)
                        || str_contains(strtolower($r['rewardName'] ?? ($r['rewardId'] ?? '')), $searchLower);
                });
            }

            if ($status !== '') {
                $all = array_filter($all, fn($r) => strtoupper($r['status'] ?? 'PENDING') === $status);
            }

            if ($dateFrom || $dateTo) {
                $fromTs = $dateFrom ? strtotime($dateFrom . ' 00:00:00') : 0;
                $toTs = $dateTo ? strtotime($dateTo . ' 23:59:59') : PHP_INT_MAX;

                $all = array_filter($all, function ($r) use ($fromTs, $toTs) {
                    $ts = 0;
                    $raw = $r['createdAt'] ?? null;
                    if ($raw) {
                        $ts = is_numeric($raw) ? (int)$raw : (strtotime((string)$raw) ?: 0);
                    }
                    return $ts >= $fromTs && $ts <= $toTs;
                });
            }

            usort($all, function ($a, $b) {
                $tsA = is_numeric($a['createdAt'] ?? 0) ? (int)$a['createdAt'] : strtotime((string)($a['createdAt'] ?? 0));
                $tsB = is_numeric($b['createdAt'] ?? 0) ? (int)$b['createdAt'] : strtotime((string)($b['createdAt'] ?? 0));
                return $tsB <=> $tsA;
            });

            $redemptions = array_values($all);
        } catch (\Exception $e) {
            Log::error('Error fetching redemptions: ' . $e->getMessage());
        }

        $totalPoints = 0;
        $pendingCount = 0;
        $approvedCount = 0;
        foreach ($redemptions as $r) {
            $totalPoints += (int)($r['ecoPoints'] ?? ($r['pointsCost'] ?? 0));
            $st = strtoupper($r['status'] ?? 'PENDING');
            if ($st === 'PENDING') $pendingCount++;
            if ($st === 'APPROVED') $approvedCount++;
        }

        return view('admin.redemptions.index', [
            'breadcrumbs' => ['Dashboard' => route('admin.dashboard'), 'Canjes' => null],
            'redemptions' => $redemptions,
            'search' => $search,
            'status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'kpis' => [
                'total' => count($redemptions),
                'points' => $totalPoints,
                'pending' => $pendingCount,
                'approved' => $approvedCount,
            ]
        ]);
    }

    public function show(string $id)
    {
        try {
            $redemption = $this->redemptionRepository->find($id);
            if (!$redemption) {
                abort(404, 'Redemption not found');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching redemption: ' . $e->getMessage());
            abort(404, 'Redemption not found');
        }

        return view('admin.redemptions.show', [
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard'),
                'Redemptions' => route('admin.redemptions.index'),
                'Redemption Details' => null
            ],
            'redemption' => $redemption
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:PENDING,APPROVED,REJECTED'
        ]);

        try {
            $oldRedemption = $this->redemptionRepository->find($id);
            if (!$oldRedemption) {
                abort(404, 'Redemption not found');
            }

            $this->redemptionRepository->updateStatus($id, $validated['status']);

            $this->auditLogRepository->log(
                action: 'UPDATE_REDEMPTION_STATUS',
                description: "Updated redemption status to {$validated['status']}",
                userId: auth()->id() ?? 'system',
                metadata: [
                    'resource' => 'redemptions',
                    'resourceId' => $id,
                    'oldData' => ['status' => $oldRedemption['status'] ?? 'UNKNOWN'],
                    'newData' => ['status' => $validated['status']],
                ]
            );

            return redirect()->route('admin.redemptions.index')->with('success', 'Redemption status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating redemption status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating redemption status.');
        }
    }
}
